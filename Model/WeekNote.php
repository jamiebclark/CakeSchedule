<?php
class WeekNote extends SchedulerAppModel {
	public $name = 'WeekNote';
	public $belongsTo = [
		'Scheduler.Week', 
		'Scheduler.Task'
	];
}