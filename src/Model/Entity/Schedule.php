<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Schedule Entity
 *
 * @property int $id
 * @property int $id_users
 * @property int $id_services
 * @property \Cake\I18n\DateTime $date_start
 * @property \Cake\I18n\DateTime $date_end
 * @property string $status
 * @property string $observation
 * @property \Cake\I18n\DateTime $schedule_date
 */
class Schedule extends Entity
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
        'id_users' => true,
        'id_services' => true,
        'date_start' => true,
        'date_end' => true,
        'status' => true,
        'observation' => true,
        'schedule_date' => true,
    ];
}
