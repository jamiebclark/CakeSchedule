<?php
class Team extends SchedulerAppModel {
	public $name = 'Team';
	public $actsAs = ['Scheduler.HabtmUser'];

	public $hasMany = ['TeamMember' => ['className' => 'Scheduler.TeamMember', 'dependent' => true]];
	public $hasAndBelongsToMany = [
		'ScheduleUser' => [
			'className' => 'Scheduler.ScheduleUser',
			//'with' => 'Scheduler.TeamMember',
			'joinTable' => 'sched_team_members',
			'foreignKey' => 'team_id',
			'associationForeignKey' => 'schedule_user_id'
		]
	];

}