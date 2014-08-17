<?php
App::uses('Hash', 'Utility');

class ScheduleUser extends SchedulerAppModel {
	public $name = 'ScheduleUser';
	
	public $useTable = 'users';
	public $tablePrefix = false;

	public $hasMany = [
		'CreatedTask' => [
		 	'foreignKey' => 'schedule_user_id',
			'className' => 'Scheduler.Task',
			'foreignKey' => 'creator_id',
		],
		'CreatedProject' => [
		 	'foreignKey' => 'schedule_user_id',
			'className' => 'Scheduler.TeamProject',
			'foreignKey' => 'creator_id',
		],
		'Time' => ['className' => 'Scheduler.Time', 'dependent' => true, 'foreignKey' => 'schedule_user_id'],
		//'Week' => ['className' => 'Scheduler.Week', 'dependent' => true, 'foreignKey' => 'schedule_user_id'],
		'TeamMember' => ['className' => 'Scheduler.TeamMember', 'dependent' => true, 'foreignKey' => 'schedule_user_id']
	];

	public $hasAndBelongsToMany = [
		'TeamProject' => [
			'className' => 'Scheduler.TeamProject',
			'joinTable' => 'sched_team_projects_users',
			'foreignKey' => 'schedule_user_id',
			'associationForeignKey' => 'team_project_id',
		],
		'Task' => [
			'className' => 'Scheduler.Task',
			'joinTable' => 'sched_tasks_users',
			'foreignKey' => 'schedule_user_id',
			'associationForeignKey' => 'task_id',
		],
		'Team' => [
			'className' => 'Scheduler.Team',
			'with' => 'Scheduler.TeamMember',
			'foreignKey' => 'schedule_user_id',
			'associationForeignKey' => 'team_id',
		]
	];

	public function findTeams($id, $type = 'all') {
		$db = $this->getDataSource();
		$result = $this->Team->find('all', [
			'joins' => [
				[
					'table' => $db->fullTableName($this->TeamMember),
					'alias' => 'TeamMemberFilter',
					'conditions' => ['TeamMemberFilter.team_id = Team.id']
				]
			],
			'conditions' => ['TeamMemberFilter.schedule_user_id' => $id],
			'group' => 'Team.id',
		]);
		if ($type == 'list') {
			$result = Hash::combine($result, '{n}.Team.id', '{n}.Team.title');
		}
		return $result;
	}

	public function findTeammates($id, $query = [], $type = 'all') {
		$db = $this->getDataSource();
		$query = Hash::merge([
			'fields' => [$this->escapeField('*'), 'TeamFilter.*'],
			'joins' => [
				[
					'table' => $db->fullTableName($this->TeamMember),
					'alias' => 'TeamMemberFilter',
					'conditions' => ['TeamMemberFilter.schedule_user_id = ' . $this->escapeField()]
				], [
					'table' => $db->fullTableName($this->TeamMember->Team),
					'alias' => 'TeamFilter',
					'conditions' => ['TeamFilter.id = TeamMemberFilter.team_id']
				], [
					'table' => $db->fullTableName($this->TeamMember),
					'alias' => 'TeamMemberUser',
					'conditions' => [
						'TeamMemberUser.team_id = TeamFilter.id',
						'TeamMemberUser.schedule_user_id' => $id,
					]
				]
			],
			'conditions' => ['NOT' => [$this->escapeField() => $id]],
			'group' => $this->escapeField(),
		], $query);
		$result = $this->find('all', $query);
		if ($type == 'list') {
			$result = Hash::combine($result, '{n}.ScheduleUser.id', '{n}.ScheduleUser.full_name', '{n}.TeamFilter.title');
		}
		return $result;
	}
}