<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AvailableFixture
 */
class AvailableFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'available';
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
                'week_day' => 1,
                'start_shift' => '11:46:27',
                'end_shift' => '11:46:27',
            ],
        ];
        parent::init();
    }
}
