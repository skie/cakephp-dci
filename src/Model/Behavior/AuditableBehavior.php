<?php
declare(strict_types=1);

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class AuditableBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'implementedMethods' => [
            'logOperation' => 'logOperation',
        ],
    ];

    /**
     * @var \Cake\ORM\Table
     */
    protected Table $_auditLogsTable;

    /**
     * Initialize hook
     *
     * @param array $config The configuration for this behavior.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->_auditLogsTable = TableRegistry::getTableLocator()->get('AuditLogs');
    }

    /**
     * Log an operation for the current model
     *
     * @param \Cake\ORM\Table $table The table instance
     * @param int $foreignKey The related record ID
     * @param string $operation The operation name
     * @param array $data Additional data to log
     * @return \Cake\Datasource\EntityInterface|false
     */
    public function logOperation(Table $table, int $foreignKey, string $operation, array $data = [])
    {
        $log = $this->_auditLogsTable->newEntity([
            'model' => $table->getAlias(),
            'foreign_key' => $foreignKey,
            'operation' => $operation,
            'data' => json_encode($data),
            'created' => new \DateTime()
        ]);

        return $this->_auditLogsTable->save($log);
    }
}
