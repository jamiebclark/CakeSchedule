<?php
class WeekNote extends CakeScheduleAppModel {
	public $name = 'WeekNote';
	public $belongsTo = [
		'CakeSchedule.Week', 
		'CakeSchedule.Task'
	];
}