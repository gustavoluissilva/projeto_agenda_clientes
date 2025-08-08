<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ScheduleTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ScheduleTable Test Case
 */
class ScheduleTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ScheduleTable
     */
    protected $Schedule;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Schedule',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Schedule') ? [] : ['className' => ScheduleTable::class];
        $this->Schedule = $this->getTableLocator()->get('Schedule', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Schedule);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\ScheduleTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
