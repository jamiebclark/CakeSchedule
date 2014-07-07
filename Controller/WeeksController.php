<?php
App::uses('ScheduleTime', 'CakeSchedule.Utility');

class WeeksController extends CakeScheduleAppController {
	public $name = 'Weeks';

	public function index($weekStart = null) {
		$weekStart = ScheduleTime::weekStart($weekStart);
		$Team = ClassRegistry::init('CakeSchedule.Team');

		$teams = $Team->find('all', [
			'contain' => [
				'User',
				'TeamMember' => [
					'User',
					'Week' => ['conditions' => ['Week.week_start' => $weekStart]]
				]
			],
			'userId' => $this->Auth->user('id'),
		]);
		$this->set(compact('teams', 'weekStart'));
	}

	public function add($weekStart = null) {
		$this->FormData->addData([
			'default' => [
				'Week' => [
					'week_start' => ScheduleTime::weekStart($weekStart),
					'user_id' => $this->Auth->user('id'),
				]
			]
		]);
		$this->render('/Elements/weeks/form');
	}

	public function view($id = null) {
		$this->FormData->findModel($id);
	}

	public function edit($id = null) {
		$this->FormData->editData($id);
		$this->render('/Elements/weeks/form');
	}

	public function delete($id = null) {
		$this->FormData->deleteData($id);
	}
}