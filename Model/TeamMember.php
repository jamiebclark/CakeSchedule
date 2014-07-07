<?php
class TeamMember extends CakeScheduleAppModel {
	public $name = 'TeamMember';
	public $hasMany = ['Week' => ['className' => 'CakeSchedule.Week', 'dependent' => true]];
	public $belongsTo = ['CakeSchedule.Team', 'CakeSchedule.User'];
}