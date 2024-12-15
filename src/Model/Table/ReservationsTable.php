<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Reservations Model
 *
 * @property \App\Model\Table\RoomsTable&\Cake\ORM\Association\BelongsTo $Rooms
 * @property \App\Model\Table\GuestsTable&\Cake\ORM\Association\BelongsTo $Guests
 * @property \App\Model\Table\ReservationGuestsTable&\Cake\ORM\Association\HasMany $ReservationGuests
 *
 * @method \App\Model\Entity\Reservation newEmptyEntity()
 * @method \App\Model\Entity\Reservation newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Reservation> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Reservation get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Reservation findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Reservation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Reservation> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Reservation|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Reservation saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Reservation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Reservation>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Reservation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Reservation> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Reservation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Reservation>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Reservation>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Reservation> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReservationsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('reservations');
        $this->setDisplayField('status');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Auditable');

        $this->belongsTo('Rooms', [
            'foreignKey' => 'room_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('PrimaryGuest', [
            'className' => 'Guests',
            'foreignKey' => 'primary_guest_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsToMany('Guests', [
            'through' => 'ReservationGuests',
            'foreignKey' => 'reservation_id',
            'targetForeignKey' => 'guest_id',
        ]);

        $this->hasMany('ReservationGuests', [
            'foreignKey' => 'reservation_id',
            'dependent' => true,
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('room_id')
            ->notEmptyString('room_id');

        $validator
            ->integer('primary_guest_id')
            ->notEmptyString('primary_guest_id');

        $validator
            ->date('check_in')
            ->requirePresence('check_in', 'create')
            ->notEmptyDate('check_in');

        $validator
            ->date('check_out')
            ->requirePresence('check_out', 'create')
            ->notEmptyDate('check_out');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->notEmptyString('status');

        $validator
            ->decimal('total_price')
            ->requirePresence('total_price', 'create')
            ->notEmptyString('total_price');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['room_id'], 'Rooms'), ['errorField' => 'room_id']);
        $rules->add($rules->existsIn(['primary_guest_id'], 'Guests'), ['errorField' => 'primary_guest_id']);

        return $rules;
    }
}
