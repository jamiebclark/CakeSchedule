<?php
App::uses('ScheduleTime', 'Scheduler.Utility');
App::uses('SchedulerAppModel', 'Scheduler.Model');

class Time extends SchedulerAppModel {
	public $name = 'Time';
	public $belongsTo = [
		'Scheduler.Task', 
		'Scheduler.ScheduleUser'
	];

	public $order = ['Time.started' => 'ASC', 'Time.stopped' => 'ASC'];

/*
	public function beforeFind($query = []) {
		$query = array_merge([
			'startDate' => false,
			'endDate' => false,
			'type' => 'day',
		], $query);

		if (!empty($query['date'])) {
			$date = date('Y-m-d H:i:s', strtotime($query['date']));
			unset($query['date']);
			switch ($query['dateType']):
				case 'day':
				default:
					$

				break;
			endswitch;
		}
	}
	*/

	public function findNextOpenTime($userId, $start = null, $stop = null) {
		if (empty($start)) {
			$start = ScheduleTime::dateStart();
		}
		$query = [
			'conditions' => [
				$this->escapeField('schedule_user_id') => $userId,
				$this->escapeField('started') . ' >=' => $start,
			],
			'order' => ['Time.stopped' => 'DESC']
		];
		if (!empty($stop)) {
			$query['conditions'][$this->escapeField('stopped') . ' <='] = ScheduleTime::dateEnd($stop);
		}
		$result = $this->find('first', $query);
		if (empty($result)) {
			$dayTime = ScheduleTime::startOfWorkDay($start);
		} else {
			$dayTime = $result[$this->alias]['stopped'];
		}
		return strtotime($dayTime);
	}


	public function findDays($query = []) {
		$result = $this->find('all', $query);
		$return = [];
		foreach ($result as $row) {
			$dayStart = date('Y-m-d', strtotime($row[$this->alias]['started']));
			$dayStop  = date('Y-m-d', strtotime($row[$this->alias]['stopped']));
			if ($dayStart != $dayStop) {
				$return[$dayStart][] = [$this->alias => ['stopped' => ScheduleTime::dateEnd($dayStart)] + $row[$this->alias]];
				$return[$dayStop][]  = [$this->alias => ['started' => ScheduleTime::dateStart($dayStop)] + $row[$this->alias]];
			} else {
				$return[$dayStart][] = $row;
			}
		}
		return $return;
	}
}