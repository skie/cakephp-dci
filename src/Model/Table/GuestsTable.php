<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Guests Model
 *
 * @property \App\Model\Table\ReservationGuestsTable&\Cake\ORM\Association\HasMany $ReservationGuests
 *
 * @method \App\Model\Entity\Guest newEmptyEntity()
 * @method \App\Model\Entity\Guest newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Guest> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Guest get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Guest findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Guest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Guest> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Guest|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Guest saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Guest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Guest>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Guest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Guest> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Guest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Guest>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Guest>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Guest> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsTable extends Table
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

        $this->setTable('guests');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Auditable');

        $this->hasMany('Reservations', [
            'foreignKey' => 'primary_guest_id',
        ]);

        $this->belongsToMany('AdditionalReservations', [
            'className' => 'Reservations',
            'through' => 'ReservationGuests',
            'foreignKey' => 'guest_id',
            'targetForeignKey' => 'reservation_id',
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('phone')
            ->maxLength('phone', 20)
            ->requirePresence('phone', 'create')
            ->notEmptyString('phone');

        $validator
            ->scalar('loyalty_level')
            ->maxLength('loyalty_level', 20)
            ->notEmptyString('loyalty_level');

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
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }
}
