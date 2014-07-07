<?php
class Team extends CakeScheduleAppModel {
	public $name = 'Team';
	public $actsAs = ['CakeSchedule.HabtmUser'];

	public $hasMany = ['TeamMember' => ['className' => 'CakeSchedule.TeamMember', 'dependent' => true]];
	public $hasAndBelongsToMany = [
		'User' => [
			'className' => 'CakeSchedule.User',
			//'with' => 'CakeSchedule.TeamMember',
			'joinTable' => 'sched_team_members',
			'foreignKey' => 'team_id',
			'associationForeignKey' => 'user_id'
		]
	];

}