<?php
class Task extends CakeScheduleAppModel {
	public $name = 'Task';
	public $actsAs = ['CakeSchedule.HabtmUser'];

	public $order = [
		'IF (Task.completed IS NOT NULL, Task.completed, Task.deadline)' => 'ASC',
		'Task.created' => 'DESC',
	];

	public $hasMany = ['CakeSchedule.WeekNote'];

	public $belongsTo = [
		'CakeSchedule.Project',
		'Creator' => [
			'className' => 'CakeSchedule.User',
			'foreignKey' => 'creator_id',
		]
	];

	public $hasAndBelongsToMany = [
		'User' => [
			'className' => 'CakeSchedule.User',
			'joinTable' => 'sched_tasks_users',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'user_id',
		],
		'Week' => [
			'className' => 'CakeSchedule.Week',
			'joinTable' => 'sched_tasks_weeks',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'week_id',
		],
	];
}