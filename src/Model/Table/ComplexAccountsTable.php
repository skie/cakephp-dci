<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ComplexAccounts Model
 *
 * @method \App\Model\Entity\ComplexAccount newEmptyEntity()
 * @method \App\Model\Entity\ComplexAccount newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\ComplexAccount> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ComplexAccount get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\ComplexAccount findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\ComplexAccount patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\ComplexAccount> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ComplexAccount|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\ComplexAccount saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\ComplexAccount>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ComplexAccount>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ComplexAccount>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ComplexAccount> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ComplexAccount>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ComplexAccount>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\ComplexAccount>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\ComplexAccount> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ComplexAccountsTable extends Table
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

        $this->setTable('complex_accounts');
        $this->setDisplayField('account_type');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Auditable');
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
            ->decimal('balance')
            ->notEmptyString('balance');

        $validator
            ->scalar('account_type')
            ->maxLength('account_type', 50)
            ->notEmptyString('account_type');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->notEmptyString('status');

        $validator
            ->boolean('is_frozen')
            ->notEmptyString('is_frozen');

        return $validator;
    }
}
