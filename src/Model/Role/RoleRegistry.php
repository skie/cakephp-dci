<?php
declare(strict_types=1);

namespace App\Model\Role;

use Cake\Core\ObjectRegistry;
use Cake\Datasource\EntityInterface;
use InvalidArgumentException;

class RoleRegistry extends ObjectRegistry
{
    private EntityInterface $_entity;

    public function __construct(EntityInterface $entity)
    {
        $this->_entity = $entity;
    }

    /**
     * Should return a string identifier for the object being loaded.
     *
     * @param string $class The class name to register.
     * @return string
     */
    protected function _resolveClassName(string $class): string
    {
        if (class_exists($class)) {
            return $class;
        }

        $className = 'App\\Model\\Role\\' . $class . 'Role';
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Role class for '{$class}' not found");
        }

        return $className;
    }

    /**
     * Create an instance of a role.
     *
     * @param string $class The class to create.
     * @param string $alias The alias of the role.
     * @param array $config The config array for the role.
     * @return \App\Model\Role\RoleBehavior
     */
    protected function _create($class, string $alias, array $config): RoleBehavior
    {
        return new $class($this->_entity, $config);
    }

    /**
     * Get the key used to store roles in the registry.
     *
     * @param string $name The role name to get a key for.
     * @return string
     */
    protected function _resolveKey(string $name): string
    {
        return strtolower($name);
    }

    /**
     * Clear all roles from the registry.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->reset();
    }

    /**
     * @inheritDoc
     */
    protected function _throwMissingClassError(string $class, ?string $plugin): void
    {
        throw new InvalidArgumentException("Role class for '{$class}' not found");
    }
}
