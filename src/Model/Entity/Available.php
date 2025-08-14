<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Available Entity
 *
 * @property int $id
 * @property int|null $week_day
 * @property \Cake\I18n\Time $start_shift
 * @property \Cake\I18n\Time $end_shift
 */
class Available extends Entity
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
        'week_day' => true,
        'start_shift' => true,
        'end_shift' => true,
    ];
}
