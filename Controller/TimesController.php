<?php
App::uses('CakeTime', 'Utility');
class TimesController extends CakeScheduleAppController {
	public $name = 'Times';

	public function index($userId = null, $start = null, $stop = null) {
		if (empty($userId)) {
			$userId = $this->Auth->user('id');
		}
		if (empty($start)) {
			$start = (date('l') == 'Sunday') ? 'today' : 'last Sunday';
		}
		if (empty($stop)) {
			$stop = 'next Sunday';
		}
		$times = $this->Time->findDays([
			'conditions' => [
				'OR' => [
					CakeTime::daysAsSql($start, $stop, 'Time.started'),
					CakeTime::daysAsSql($start, $stop, 'Time.stopped'),
				],
				'Time.user_id' => $userId,
			]
		]);
		$this->set(compact('userId', 'start', 'stop', 'times'));
	}

	public function add () {
		$started = date('Y-m-d ') . Configure::read('CakeSchedule.dayStartTime');
		$userId = $this->Auth->user('id');

		foreach (['started', 'stopped', 'userId'] as $pass) {
			if (isset($this->request->params['named'][$pass])) {
				$varName = Inflector::underscore($pass);
				$$varName = $this->request->params['named'][$pass];
			}
		}

		if (empty($this->request->params['named']['stopped'])) {
			$stopped = date('Y-m-d H:i:s', strtotime("$started +1 hour"));
		}


		$this->FormData->addData(['default' => ['Time' => compact('started', 'stopped') + ['user_id' => $userId]]]);
		$this->render('/Elements/times/form');
	}

	public function view($id) {
		$result = $this->FormData->findModel($id);
		$this->redirect([
			'action' => 'index',
			$result['Time']['user_id'],
			$result['Time']['started']
		]);
	}

	public function edit($id) {
		$this->FormData->editData($id);
		$this->render('/Elements/times/form');
	}

	public function _setFormElements() {
		$tasks = $this->Time->Task->find('list', [
			'conditions' => [
				'Task.completed' => null
			]
		]);
		$this->set(compact('tasks'));
	}
}