<?php
declare(strict_types=1);

namespace App\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\ConnectionHelper;

abstract class BaseContext implements Context
{

    /**
     * BootstrapContext constructor.
     *
     * @param string $bootstrap
     */
    public function __construct(string $bootstrap = null)
    {
    }


    protected function initialize(): void
    {
        require_once dirname(__DIR__, 3) . '/tests/bootstrap.php';
        require_once dirname(dirname(dirname(__DIR__))) . '/config/bootstrap.php';
        ConnectionHelper::addTestAliases();
        Configure::write('debug', true);
    }

    protected function getTableLocator()
    {
        return TableRegistry::getTableLocator();
    }
}
