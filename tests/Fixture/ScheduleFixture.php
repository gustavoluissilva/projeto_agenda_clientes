<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ScheduleFixture
 */
class ScheduleFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'schedule';
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
                'id_users' => 1,
                'id_services' => 1,
                'date_start' => '2025-08-08 19:32:59',
                'date_end' => '2025-08-08 19:32:59',
                'status' => 'Lorem ipsum dolor sit amet',
                'observation' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'schedule_date' => 1754681579,
            ],
        ];
        parent::init();
    }
}
