<?php
declare(strict_types=1);

namespace App\Context\Contracts;

interface RoleInterface
{
    public function bind($player): void;
    public function unbind(): void;
    public function getPlayer();
}
