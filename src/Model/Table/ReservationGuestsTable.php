<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReservationGuests Model
 *
 * @property \App\Model\Table\ReservationsTable&\Cake\ORM\Association\BelongsTo $Reservations
 * @property \App\Model\Table\GuestsTable&\Cake\ORM\Association\BelongsTo $Guests
 *
 * @method \App\Model\Entity\ReservationGuest newEmptyEntity()
 * @method \App\Model\Entity\ReservationGuest newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ReservationGuest> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReservationGuest get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ReservationGuest findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ReservationGuest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ReservationGuest> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReservationGuest|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ReservationGuest saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ReservationGuest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ReservationGuest>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ReservationGuest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ReservationGuest> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ReservationGuest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ReservationGuest>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ReservationGuest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ReservationGuest> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReservationGuestsTable extends Table
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

        $this->setTable('reservation_guests');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Reservations', [
            'foreignKey' => 'reservation_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Guests', [
            'foreignKey' => 'guest_id',
            'joinType' => 'INNER',
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
            ->integer('reservation_id')
            ->notEmptyString('reservation_id');

        $validator
            ->integer('guest_id')
            ->notEmptyString('guest_id');

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
        $rules->add($rules->existsIn(['reservation_id'], 'Reservations'), ['errorField' => 'reservation_id']);
        $rules->add($rules->existsIn(['guest_id'], 'Guests'), ['errorField' => 'guest_id']);

        return $rules;
    }
}
