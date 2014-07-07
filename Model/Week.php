<?php
class Week extends CakeScheduleAppModel {
	public $name = 'Week';
	public $hasMany = ['CakeSchedule.WeekNote'];
	public $belongsTo = ['CakeSchedule.TeamMember'];
	public $hasAndBelongsToMany = [
		'Task' => [
			'className' => 'CakeSchedule.Task',
			'foreignKey' => 'week_id',
			'associationForeignKey' => 'task_id',
			'joinTable' => 'sched_tasks_weeks',
		]
	];
	public $order = ['Week.week_start' => 'DESC'];
}