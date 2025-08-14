<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Schedule Model
 */
class ScheduleTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('schedule');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        // ADICIONE AS ASSOCIAÇÕES AQUI
        
        // Esta linha diz: "Um Agendamento (Schedule) PERTENCE A UM Serviço (Service)"
        // A conexão é feita pela chave estrangeira 'id_services'.
        $this->belongsTo('Services', [
            'foreignKey' => 'id_services',
            'joinType' => 'INNER', // Garante que um agendamento só apareça se tiver um serviço válido
        ]);

        // Esta linha diz: "Um Agendamento (Schedule) PERTENCE A UM Usuário (User)"
        // A conexão é feita pela chave estrangeira 'id_users'.
        $this->belongsTo('Users', [
            'foreignKey' => 'id_users',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        // Você pode adicionar regras de validação para agendamentos aqui no futuro
        $validator
            ->dateTime('date_start')
            ->notEmptyDateTime('date_start');

        // ... etc

        return $validator;
    }
}