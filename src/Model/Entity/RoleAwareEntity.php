<?php
declare(strict_types=1);

namespace App\Model\Entity;

use App\Model\Role\RoleBehavior;
use App\Model\Role\RoleRegistry;
use Cake\ORM\Entity;
use BadMethodCallException;

class RoleAwareEntity extends Entity
{
    private ?RoleRegistry $_roles = null;
    private array $_roleMethods = [];

    protected function _getRoleRegistry(): RoleRegistry
    {
        if ($this->_roles === null) {
            $this->_roles = new RoleRegistry($this);
        }
        return $this->_roles;
    }

    public function addRole(string $role, array $config = []): void
    {
        $roleInstance = $this->_getRoleRegistry()->load($role, $config);

        foreach ($roleInstance->implementedMethods() as $method => $callable) {
            $this->_roleMethods[$method] = $role;
        }
    }

    public function removeRole(string $role): void
    {
        $this->_roleMethods = array_filter(
            $this->_roleMethods,
            fn($roleType) => $roleType !== $role
        );

        $this->_getRoleRegistry()->unload($role);
    }

    public function hasRole(string $role): bool
    {
        return $this->_getRoleRegistry()->has($role);
    }

    protected function getRole(string $role): RoleBehavior
    {
        return $this->_getRoleRegistry()->load($role);
    }

    public function __call(string $method, array $arguments)
    {
        if (isset($this->_roleMethods[$method])) {
            $role = $this->getRole($this->_roleMethods[$method]);
            return $role->$method(...$arguments);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist',
            static::class,
            $method
        ));
    }

    public function hasMethod(string $method): bool
    {
        return isset($this->_roleMethods[$method]);
    }
}
