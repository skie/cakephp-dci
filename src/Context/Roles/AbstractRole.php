<?php
declare(strict_types=1);

namespace App\Context\Roles;

use App\Context\Contracts\RoleInterface;

abstract class AbstractRole implements RoleInterface
{
    protected $player;

    public function bind($player): void
    {
        if (!$this->validatePlayer($player)) {
            throw new \InvalidArgumentException('Player does not meet role requirements');
        }
        $this->player = $player;
    }

    public function unbind(): void
    {
        $this->player = null;
    }

    public function getPlayer()
    {
        return $this->player;
    }

    abstract protected function validatePlayer($player): bool;
}
