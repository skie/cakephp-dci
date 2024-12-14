<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Account;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Accounts Model
 *
 * @method \App\Model\Entity\Account newEmptyEntity()
 * @method \App\Model\Entity\Account newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Account> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Account get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Account findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Account patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Account> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Account|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Account saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Account>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Account>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Account>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Account> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Account>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Account>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Account>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Account> deleteManyOrFail(iterable $entities, array $options = [])
 */
class AccountsTable extends Table
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

        $this->setTable('accounts');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
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
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->numeric('balance')
            ->notEmptyString('balance');

        return $validator;
    }
}
