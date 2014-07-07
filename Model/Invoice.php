<?php
class Invoice extends CakeScheduleAppModel {
	public $name = 'Invoice';
	public $belongsTo = ['CakeSchedule.User'];
	public $hasMany = ['CakeSchedule.Time'];
}