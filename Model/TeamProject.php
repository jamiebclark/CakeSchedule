<?php
App::uses('SchedulerAppModel', 'Scheduler.Model');

class TeamProject extends SchedulerAppModel {
	public $name = 'TeamProject';

	public $actsAs = ['Tree'];
	public $order = 'TeamProject.lft';

	public $hasMany = ['Scheduler.Task'];
	public $belongsTo = [
		'Creator' => [
			'className' => 'Scheduler.ScheduleUser',
			'foreignKey' => 'creator_id',
		],
		'Team' => [
			'className' => 'Scheduler.Team'
		]
	];

	public $hasAndBelongsToMany = [
		'ScheduleUser' => [
			'joinTable' => 'sched_team_projects_users',
			'className' => 'Scheduler.ScheduleUser',
			'foreignKey' => 'team_project_id',
			'associationForeignKey' => 'schedule_user_id',
		]
	];

	public $findMethods = ['slash' => true];

	public function afterSave($created, $options = []) {
		$this->save(['id' => $this->id, 'slash' => $this->getSlash($this->id)], ['callbacks' => false]);
		return parent::afterSave($created, $options);
	}

	protected function _findSlash($state, $query, $results = []) {
		if ($state == 'before') {
			return $query;
		}
		return $this->_slashParse($this->_findThreaded($state, $query, $results));
	}

	private function _slashParse($results, $return = [], $prefix = '', $separator = '/') {
		foreach ($results as $result) {
			$title = $prefix . $result[$this->alias][$this->displayField];

			$return[$result[$this->alias][$this->primaryKey]] = $title;
			if (!empty($result['children'])) {
				$return = $this->_slashParse($result['children'], $return, $title . $separator, $separator);
			}
		}
		return $return;
	}

	private function getSlash($id) {
		$path = $this->getPath($id, [$this->displayField], true);
		debug([
			$id,
			$this->getPath($id),
			$path,
			Hash::extract($path, '{n}.' . $this->alias . '.' . $this->displayField),
			implode('/', Hash::extract($path, '{n}.' . $this->alias . '.' . $this->displayField)),
		]);
		//die();
		return implode('/', Hash::extract($path, '{n}.' . $this->alias . '.' . $this->displayField));
	}
}