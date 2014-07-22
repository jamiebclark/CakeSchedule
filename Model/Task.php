<?php
class Task extends SchedulerAppModel {
	public $name = 'Task';
	public $actsAs = ['Scheduler.HabtmUser'];

	public $order = [
		'IF (Task.completed IS NOT NULL, Task.completed, Task.deadline)' => 'ASC',
		'Task.created' => 'DESC',
	];

	public $hasMany = ['Scheduler.WeekNote'];

	public $belongsTo = [
		'Scheduler.Project',
		'Creator' => [
			'className' => 'Scheduler.ScheduleUser',
			'foreignKey' => 'creator_id',
		]
	];

	public $hasAndBelongsToMany = [
		'ScheduleUser' => [
			'className' => 'Scheduler.ScheduleUser',
			'joinTable' => 'sched_tasks_users',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'schedule_user_id',
		],
		'Week' => [
			'className' => 'Scheduler.Week',
			'joinTable' => 'sched_tasks_weeks',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'week_id',
		],
	];
}