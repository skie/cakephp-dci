<?php
declare(strict_types=1);

namespace App\Model\Role;

use Cake\Datasource\EntityInterface;
use Cake\Event\EventDispatcherInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Utility\Hash;

abstract class RoleBehavior implements EventDispatcherInterface
{
    use EventDispatcherTrait;

    protected EntityInterface $_entity;
    protected array $_config;

    protected $_defaultConfig = [];

    public function __construct(EntityInterface $entity, array $config = [])
    {
        $this->_entity = $entity;
        $this->_config = array_merge($this->_defaultConfig, $config);
        $this->initialize($config);
    }

    /**
     * Initialize hook - like CakePHP behaviors
     */
    public function initialize(array $config): void
    {
    }

    /**
     * Get behavior config
     */
    public function getConfig(?string $key = null, $default = null): mixed
    {
        return Hash::get($this->_config, $key, $default);
    }

    /**
     * Check if entity has specific property/method
     */
    protected function hasProperty(string $property): bool
    {
        return $this->_entity->has($property);
    }

    /**
     * Get entity property
     */
    protected function getProperty(string $property): mixed
    {
        return $this->_entity->get($property);
    }

    /**
     * Set entity property
     */
    protected function setProperty(string $property, mixed $value): void
    {
        $this->_entity->set($property, $value);
    }

    /**
     * Get implemented methods - similar to CakePHP behaviors
     */
    public function implementedMethods(): array
    {
        return [];
    }

    /**
     * Get implemented events
     */
    public function implementedEvents(): array
    {
        return [];
    }
}
