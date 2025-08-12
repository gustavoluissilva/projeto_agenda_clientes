<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AvailableTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AvailableTable Test Case
 */
class AvailableTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AvailableTable
     */
    protected $Available;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.Available',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Available') ? [] : ['className' => AvailableTable::class];
        $this->Available = $this->getTableLocator()->get('Available', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Available);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\AvailableTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
