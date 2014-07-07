<?php
class User extends CakeScheduleAppModel {
	public $name = 'User';
	public $hasMany = [
		'CreatedTask' => [
			'className' => 'CakeSchedule.Task',
			'foreignKey' => 'creator_id',
		],
		'CreatedProject' => [
			'className' => 'CakeSchedule.Project',
			'foreignKey' => 'creator_id',
		],
		'CakeSchedule.Time' => ['dependent' => true],
		'CakeSchedule.Week' => ['dependent' => true],
		'CakeSchedule.TeamMember' => ['dependent' => true]
	];

	public $hasAndBelongsToMany = [
		'Project' => [
			'className' => 'CakeSchedule.Project',
			'joinTable' => 'sched_projects_users',
			'foreignKey' => 'user_id',
			'assocateForeignKey' => 'project_id',
		],
		'Task' => [
			'className' => 'CakeSchedule.Task',
			'joinTable' => 'sched_tasks_users',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'task_id',
		],
		'Team' => [
			'className' => 'CakeSchedule.Team',
			'with' => 'CakeSchedule.TeamMember',
			'foreignKey' => 'user_id',
			'assocationForeignKey' => 'team_id',
		]
	];
}