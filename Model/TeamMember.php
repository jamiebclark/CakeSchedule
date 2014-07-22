<?php
class TeamMember extends SchedulerAppModel {
	public $name = 'TeamMember';
	public $hasMany = ['Week' => ['className' => 'Scheduler.Week', 'dependent' => true]];
	public $belongsTo = ['Scheduler.Team', 'Scheduler.ScheduleUser'];
}