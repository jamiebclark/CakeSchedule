<?php
class ScheduleUser extends SchedulerAppModel {
	public $name = 'ScheduleUser';
	
	public $useTable = 'users';
	public $tablePrefix = false;

	public $hasMany = [
		'CreatedTask' => [
			'className' => 'Scheduler.Task',
			'foreignKey' => 'creator_id',
		],
		'CreatedProject' => [
			'className' => 'Scheduler.Project',
			'foreignKey' => 'creator_id',
		],
		'Scheduler.Time' => ['dependent' => true],
		'Scheduler.Week' => ['dependent' => true],
		'Scheduler.TeamMember' => ['dependent' => true]
	];

	public $hasAndBelongsToMany = [
		'Project' => [
			'className' => 'Scheduler.Project',
			'joinTable' => 'sched_projects_users',
			'foreignKey' => 'schedule_user_id',
			'assocateForeignKey' => 'project_id',
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
			'assocationForeignKey' => 'team_id',
		]
	];
}