<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Schedule Model
 *
 * @method \App\Model\Entity\Schedule newEmptyEntity()
 * @method \App\Model\Entity\Schedule newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Schedule> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Schedule get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Schedule findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Schedule patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Schedule> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Schedule|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Schedule saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Schedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Schedule>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Schedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Schedule> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Schedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Schedule>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Schedule>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Schedule> deleteManyOrFail(iterable $entities, array $options = [])
 */
class ScheduleTable extends Table
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

        $this->setTable('schedule');
        $this->setDisplayField('status');
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
            ->integer('id_users')
            ->requirePresence('id_users', 'create')
            ->notEmptyString('id_users');

        $validator
            ->integer('id_services')
            ->requirePresence('id_services', 'create')
            ->notEmptyString('id_services');

        $validator
            ->dateTime('date_start')
            ->requirePresence('date_start', 'create')
            ->notEmptyDateTime('date_start');

        $validator
            ->dateTime('date_end')
            ->requirePresence('date_end', 'create')
            ->notEmptyDateTime('date_end');

        $validator
            ->scalar('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->scalar('observation')
            ->requirePresence('observation', 'create')
            ->notEmptyString('observation');

        $validator
            ->dateTime('schedule_date')
            ->requirePresence('schedule_date', 'create')
            ->notEmptyDateTime('schedule_date');

        return $validator;
    }
}
