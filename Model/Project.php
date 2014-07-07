<?php
class Project extends CakeScheduleAppModel {
	public $name = 'Project';

	public $hasMany = ['CakeSchedule.Task'];
	public $belongsTo = [
		'Creator' => [
			'className' => 'CakeSchedule.User',
			'foreignKey' => 'creator_id',
		]
	];

	public $hasAndBelongsToMany = [
		'User' => [
			'className' => 'CakeSchedule.User',
			'foreignKey' => 'project_id',
			'assocationForeignKey' => 'user_id',
		]
	];
}