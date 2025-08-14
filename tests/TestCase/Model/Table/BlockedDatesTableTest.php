<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BlockedDatesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BlockedDatesTable Test Case
 */
class BlockedDatesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BlockedDatesTable
     */
    protected $BlockedDates;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.BlockedDates',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('BlockedDates') ? [] : ['className' => BlockedDatesTable::class];
        $this->BlockedDates = $this->getTableLocator()->get('BlockedDates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->BlockedDates);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\BlockedDatesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
