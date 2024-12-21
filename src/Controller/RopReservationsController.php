<?php
declare(strict_types=1);

namespace App\Controller;

use App\Reservation\{ReservationData, ReservationOperations};
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\DateTime;

class RopReservationsController extends AppController
{
    public function add()
    {
        /** @var \App\Model\Table\RoomsTable $Rooms */
        $Rooms = $this->fetchTable('Rooms');
        /** @var \App\Model\Table\GuestsTable $Guests */
        $Guests = $this->fetchTable('Guests');

        $rooms = $Rooms->find('list')->where(['status' => 'available']);
        $guests = $Guests->find('list');

        $this->set(compact('rooms', 'guests'));


        if ($this->request->is('post')) {
            try {
                $room = $Rooms->get($this->request->getData('room_id'))->toArray();
                $primaryGuest = $Guests->get($this->request->getData('primary_guest_id'))->toArray();

                $additionalGuests = [];
                if ($this->request->getData('additional_guest_ids')) {
                    $additionalGuests = $Guests->find()
                        ->where(['id IN' => $this->request->getData('additional_guest_ids')])
                        ->all()
                        ->map(fn($guest) => $guest->toArray())
                        ->toArray();
                }

                $data = new ReservationData(
                    room: $room,
                    primaryGuest: $primaryGuest,
                    additionalGuests: $additionalGuests,
                    checkIn: new DateTime($this->request->getData('check_in')),
                    checkOut: new DateTime($this->request->getData('check_out'))
                );

                /** @var \Cake\Database\Connection $connection */
                $connection = $this->fetchTable('Reservations')->getConnection();

                return $connection->transactional(function($connection) use ($data) {
                    $result = ReservationOperations::validateAvailability($data)
                        // First validate and calculate price
                        ->map(fn($data) => $data->withState('reservation_time', time()))
                        // Create reservation with error handling
                        ->tryCatch(fn($data) => ReservationOperations::createReservation($data))
                        // Send confirmation email (might fail)
                        ->bind(fn($data) => ReservationOperations::sendConfirmationEmail($data))
                        // Log the reservation (with error handling)
                        ->tryCatch(fn($data) => ReservationOperations::logReservation($data))
                        // Update room status (simple transformation)
                        ->map(fn($data) => $data->withState('room_status', 'occupied'))
                        // Calculate loyalty points (simple transformation)
                        ->map(fn($data) => $data->withState(
                            'loyalty_points',
                            floor($data->getState('total_price') * 0.1)
                        ))
                        // Update guest loyalty points (with error handling)
                        ->tryCatch(fn($data) => ReservationOperations::updateGuestLoyaltyPoints($data))
                        // Log all operations for audit
                        ->tee(fn($data) => error_log(sprintf(
                            "Reservation completed: %s, Points earned: %d",
                            $data->getState('reservation_id'),
                            $data->getState('loyalty_points')
                        )));

                    return $result->match(
                        success: function($data) {
                            $this->Flash->success(__('Reservation confirmed! Your confirmation number is: {0}',
                                $data->getState('reservation_id')
                            ));
                            return $this->redirect(['action' => 'view', $data->getState('reservation_id')]);
                        },
                        failure: function($error) {
                            if ($error instanceof \Exception) throw $error;
                            throw new \RuntimeException($error);
                        }
                    );
                });

            } catch (\Exception $e) {
                $this->Flash->error(__('Unable to complete reservation: {0}', $e->getMessage()));
            }
        }
    }

    public function view($id = null)
    {
        $reservation = $this->fetchTable('Reservations')->get($id, [
            'contain' => [
                'Rooms',
                'PrimaryGuest',
                'Guests'
            ]
        ]);

        $this->set(compact('reservation'));
    }

    public function index()
    {
        $reservations = $this->fetchTable('Reservations')->find()
            ->contain([
                'Rooms',
                'PrimaryGuest'
            ])
            ->orderDesc('Reservations.created');

        $this->set(compact('reservations'));
    }
}
