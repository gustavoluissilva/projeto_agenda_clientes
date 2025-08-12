<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BlockedDatesFixture
 */
class BlockedDatesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'start_date' => '2025-08-12 11:55:42',
                'end_date' => '2025-08-12 11:55:42',
                'reason' => 'Lorem ipsum dolor sit amet',
            ],
        ];
        parent::init();
    }
}
