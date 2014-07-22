<?php
class Invoice extends SchedulerAppModel {
	public $name = 'Invoice';
	public $belongsTo = ['Scheduler.ScheduleUser'];
	public $hasMany = ['Scheduler.Time'];
}