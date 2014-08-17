<?php
class Task extends SchedulerAppModel {
	public $name = 'Task';
	//public $actsAs = ['Scheduler.HabtmUser'];

	public $order = [
		'IF (Task.completed IS NOT NULL, Task.completed, Task.deadline)' => 'ASC',
		'Task.created' => 'DESC',
	];

	public $hasMany = ['Scheduler.WeekNote'];

	public $belongsTo = [
		'Scheduler.TeamProject',
		'Creator' => [
			'className' => 'Scheduler.ScheduleUser',
			'foreignKey' => 'creator_id',
		],
		'ScheduleUser' => [
			'className' => 'Scheduler.ScheduleUser',
			'foreignKey' => 'schedule_user_id',
		],
	];

	public $hasAndBelongsToMany = [
		/*
		'ScheduleUser' => [
			'className' => 'Scheduler.ScheduleUser',
			'joinTable' => 'sched_tasks_users',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'schedule_user_id',
		],
		*/
		'Week' => [
			'className' => 'Scheduler.Week',
			'joinTable' => 'sched_tasks_weeks',
			'foreignKey' => 'task_id',
			'associationForeignKey' => 'week_id',
		],
	];

	public function beforeValidate($query = []) {
		$data =& $this->getData();
		$data['title'] = trim($data['title'], " \t\n\r");

		return parent::beforeValidate($query);
	}

	public function beforeSave($options = []) {
		$data =& $this->getData();		

		if ($data['completed'] === '1') {
			$data['completed'] = ScheduleTime::dateTime();
		} else if ($data['completed'] === '0') {
			$data['completed'] = null;
		}

		return parent::beforeSave($options);
	}

	public function afterSave($created, $options = []) {
		$this->clean();
		return parent::afterSave($created, $options);
	}

	public function findUserTasks($userId) {
		return $this->find('all', [
			'conditions' => [
				$this->escapeField('schedule_user_id') => $userId,
				$this->escapeField('completed') => null,
			]
		]);
	}

	// Removes any tasks with empty titles
	private function clean() {
		return $this->deleteAll(['OR' => [
			[$this->escapeField('title') => ''],
			[$this->escapeField('title') => NULL]
		]]);
	}
}