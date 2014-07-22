<?php
class Project extends SchedulerAppModel {
	public $name = 'Project';

	public $hasMany = ['Scheduler.Task'];
	public $belongsTo = [
		'Creator' => [
			'className' => 'Scheduler.ScheduleUser',
			'foreignKey' => 'creator_id',
		]
	];

	public $hasAndBelongsToMany = [
		'ScheduleUser' => [
			'className' => 'Scheduler.ScheduleUser',
			'foreignKey' => 'project_id',
			'assocationForeignKey' => 'schedule_user_id',
		]
	];
}