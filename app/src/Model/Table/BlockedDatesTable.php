<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BlockedDates Model
 *
 * @method \App\Model\Entity\BlockedDate newEmptyEntity()
 * @method \App\Model\Entity\BlockedDate newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BlockedDate> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlockedDate get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BlockedDate findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BlockedDate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BlockedDate> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlockedDate|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BlockedDate saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BlockedDate>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlockedDate>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BlockedDate>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlockedDate> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BlockedDate>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlockedDate>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BlockedDate>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlockedDate> deleteManyOrFail(iterable $entities, array $options = [])
 */
class BlockedDatesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('blocked_dates');
        $this->setDisplayField('reason');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->dateTime('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmptyDateTime('start_date');

        $validator
            ->dateTime('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmptyDateTime('end_date');

        $validator
            ->scalar('reason')
            ->maxLength('reason', 255)
            ->requirePresence('reason', 'create')
            ->notEmptyString('reason');

        return $validator;
    }
}
