<?php
class SchedulerAppModel extends AppModel {
	public $tablePrefix = 'sched_';

	public function &getData() {
		if (isset($this->data[$this->alias])) {
			$data =& $this->data[$this->alias];
		} else {
			$data =& $this->data;
		}
		return $data;
	}
}