<?php
declare(strict_types=1);

namespace App\Model\Role;

use Cake\ORM\TableRegistry;

class AuditableRole extends RoleBehavior
{
    public function implementedMethods(): array
    {
        return [
            'logOperation' => 'logOperation'
        ];
    }

    public function logOperation(string $operation, array $data): void
    {
        $table = TableRegistry::getTableLocator()->get($this->_entity->getSource());
        $table->logOperation($table, $this->_entity->id, $operation, $data);
    }
}
