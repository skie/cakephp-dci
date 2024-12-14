<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AuditLog Entity
 *
 * @property int $id
 * @property string $model
 * @property int $foreign_key
 * @property string $operation
 * @property array|null $data
 * @property \Cake\I18n\DateTime|null $created
 */
class AuditLog extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'model' => true,
        'foreign_key' => true,
        'operation' => true,
        'data' => true,
        'created' => true,
    ];
}
