<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Available Model
 *
 * @method \App\Model\Entity\Available newEmptyEntity()
 * @method \App\Model\Entity\Available newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Available> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Available get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Available findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Available patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Available> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Available|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Available saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Available>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Available>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Available>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Available> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Available>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Available>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Available>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Available> deleteManyOrFail(iterable $entities, array $options = [])
 */
class AvailableTable extends Table
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

        $this->setTable('available');
        $this->setDisplayField('id');
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
            ->integer('week_day')
            ->allowEmptyString('week_day');

        $validator
            ->time('start_shift')
            ->requirePresence('start_shift', 'create')
            ->notEmptyTime('start_shift');

        $validator
            ->time('end_shift')
            ->requirePresence('end_shift', 'create')
            ->notEmptyTime('end_shift');

        return $validator;
    }
}
