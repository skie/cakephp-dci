<?php
declare(strict_types=1);

namespace App\Controller;

use App\Context\RoomReservation\RoomReservationContext;
use Cake\I18n\DateTime;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

class ReservationsController extends AppController
{
    protected $Reservations;
    protected $Rooms;
    protected $Guests;

    public function initialize(): void
    {
        parent::initialize();
        $this->Reservations = $this->fetchTable('Reservations');
        $this->Rooms = $this->fetchTable('Rooms');
        $this->Guests = $this->fetchTable('Guests');
    }

    public function add()
    {
        if ($this->request->is('post')) {
            try {
                $room = $this->Rooms->get($this->request->getData('room_id'), [
                    'contain' => ['Reservations']
                ]);

                $primaryGuest = $this->Guests->get($this->request->getData('primary_guest_id'));

                $additionalGuests = [];
                if ($this->request->getData('additional_guest_ids')) {
                    $additionalGuests = $this->Guests->find()
                        ->where(['id IN' => $this->request->getData('additional_guest_ids')])
                        ->toArray();
                }

                $checkIn = new DateTime($this->request->getData('check_in'));
                $checkOut = new DateTime($this->request->getData('check_out'));

                $context = new RoomReservationContext(
                    $room,
                    $primaryGuest,
                    $additionalGuests,
                    $checkIn,
                    $checkOut
                );

                $reservation = $context->execute();

                $this->Flash->success(__('The reservation has been created.'));
                return $this->redirect(['action' => 'view', $reservation->id]);

            } catch (NotFoundException $e) {
                $this->Flash->error(__('Invalid room or guest.'));
            } catch (BadRequestException $e) {
                $this->Flash->error($e->getMessage());
            } catch (\Exception $e) {
                $this->Flash->error(__('The reservation could not be created. Please, try again.'));
            }
        }

        $rooms = $this->Rooms->find('list')
            ->where(['status' => 'available']);

        $guests = $this->Guests->find('list');

        $this->set(compact('rooms', 'guests'));
    }

    public function view($id = null)
    {
        $reservation = $this->Reservations->get($id, [
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
        $reservations = $this->Reservations->find()
            ->contain([
                'Rooms',
                'PrimaryGuest'
            ])
            ->orderDesc('Reservations.created');

        $this->set(compact('reservations'));
    }
}
