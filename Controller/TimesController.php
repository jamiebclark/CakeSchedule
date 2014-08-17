<?php
App::uses('CakeTime', 'Utility');
App::uses('ScheduleTime', 'Scheduler.Utility');

class TimesController extends SchedulerAppController {
	public $name = 'Times';

	public function index($userId = null, $start = null, $stop = null) {
		if (empty($userId)) {
			$userId = $this->Auth->user('id');
		}

		// Finds a highlighted time
		if (!empty($this->request->named['id'])) {
			$activeTime = $this->FormData->findModel($this->request->named['id']);
			$start = ScheduleTime::weekStart($activeTime['Time']['started']);
		}

		if (empty($start)) {
			$start = (date('l') == 'Sunday') ? 'today' : 'last Sunday';
		}
		if (empty($stop)) {
			$stop = ScheduleTime::date($start, '+1 week');
		}

		$startStamp = ScheduleTime::toTime($start);
		$stopStamp = ScheduleTIme::toTime($stop);

		if ($stopStamp < $startStamp) {
			$this->redirect($userId, $stop, $start);
		}

		$start 	= ScheduleTime::dateStart($start);
		$stop 	= ScheduleTime::dateEnd($stop);
		list($startStamp, $stopStamp) = ScheduleTime::toTime([$start, $stop]);

		// Difference between start and stop
		$offset = $stopStamp - $startStamp;

		$times = $this->FormData->findAll([
			'conditions' => [
				'OR' => [
					CakeTime::daysAsSql($start, $stop, 'Time.started'),
					CakeTime::daysAsSql($start, $stop, 'Time.stopped'),
				],
				'Time.schedule_user_id' => $userId,
			]
		], ['method' => 'findDays']);


		$nextStart 	= ScheduleTime::date($stop);
		$nextStop 	= ScheduleTime::date($stop, $offset);
		$prevStop 	= ScheduleTime::date($start, '-1 day');
		$prevStart 	= ScheduleTime::date($prevStop, -1 * $offset);

		$this->_setFormElements();
		$tasks = $this->Time->Task->findUserTasks($userId);
		$this->request->data = $this->FormData->resultsToData($tasks, [
			'model' => 'Task'
		]);
		$teamProjects = $this->Time->Task->TeamProject->find('slash', ['userId' => $userId]);
		$this->set(compact('teamProjects'));
		
		
		$nextOpenTime = $this->Time->findNextOpenTime($userId, $start, $stop);
		$this->set(compact('userId', 
			'start', 'stop', 
			'activeTime',
			'startStamp', 'stopStamp',
			'nextStart', 'nextStop',
			'prevStart', 'prevStop',
			'times', 'nextOpenTime'
			//'tasks'
		));
	}

	public function add () {
		$started = ScheduleTime::startOfWorkDay();
		$userId = $this->Auth->user('id');

		// Checks for passed information
		foreach (['started', 'stopped', 'userId'] as $pass) {
			if (isset($this->request->params['named'][$pass])) {
				$varName = Inflector::underscore($pass);
				$$varName = $this->request->params['named'][$pass];
			}
		}

		$startedStamp = ScheduleTime::toTime($started);
		$started = ScheduleTime::dateTime($startedStamp);
		
		if (ScheduleTime::isMidnight($started)) {
			$startedStamp = $this->Time->findNextOpenTime($userId, ScheduleTime::dateStart($started), ScheduleTime::dateEnd($started));
			$started = ScheduleTime::dateTime($startedStamp);
		}
		if (empty($this->request->params['named']['stopped'])) {
			$stopped = ScheduleTime::dateTime($startedStamp + 3600);
		}


		$this->FormData->addData(['default' => ['Time' => compact('started', 'stopped') + ['schedule_user_id' => $userId]]]);
		$this->render('/Elements/times/form');
	}

	public function view($id) {
		$result = $this->FormData->findModel($id);
		$this->redirect([
			'action' => 'index',
			$result['Time']['schedule_user_id'],
			'id' => $result['Time']['id'],
			//ScheduleTime::date($result['Time']['started'])

		]);
	}

	public function edit($id) {
		$this->FormData->editData($id);
		$this->render('/Elements/times/form');
	}

	public function _setFormElements() {
		$userId = $this->Auth->user('id');
		$tasks = $this->Time->Task->find('list', [
			'conditions' => [
				'Task.completed' => null,
				'Task.schedule_user_id' => $userId,
			]
		]);

		$scheduleUsers = ['' => 'Anyone', $userId => 'You'] + $this->Time->ScheduleUser->findTeammates($userId, null, 'list');
		$this->set(compact('tasks', 'scheduleUsers'));
	}
}