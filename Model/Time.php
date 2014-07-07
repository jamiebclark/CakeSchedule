<?php
class Time extends CakeScheduleAppModel {
	public $name = 'Time';
	public $belongsTo = [
		'CakeSchedule.Task', 
		'CakeSchedule.User'
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

	public function findDays($query = []) {
		$result = $this->find('all', $query);
		$return = [];
		foreach ($result as $row) {
			$dayStart = date('Y-m-d', strtotime($row[$this->alias]['started']));
			$dayStop  = date('Y-m-d', strtotime($row[$this->alias]['stopped']));
			if ($dayStart != $dayStop) {
				$return[$dayStart][] = [$this->alias => ['stopped' => $dayStart . ' 23:59:59'] + $row[$this->alias]];
				$return[$dayStop][]  = [$this->alias => ['started' => $dayStop  . ' 00:00:00'] + $row[$this->alias]];
			} else {
				$return[$dayStart][] = $row;
			}
		}
		return $return;
	}
}