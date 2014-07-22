<?php
class Week extends SchedulerAppModel {
	public $name = 'Week';
	public $hasMany = ['Scheduler.WeekNote'];
	public $belongsTo = ['Scheduler.TeamMember'];
	public $hasAndBelongsToMany = [
		'Task' => [
			'className' => 'Scheduler.Task',
			'foreignKey' => 'week_id',
			'associationForeignKey' => 'task_id',
			'joinTable' => 'sched_tasks_weeks',
		]
	];
	public $order = ['Week.week_start' => 'DESC'];
}