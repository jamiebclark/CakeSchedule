<?php
class TasksController extends CakeScheduleAppController {
	public $name = 'Tasks';

	public function index($userId = null) {
		if (empty($userId)) {
			$userId = $this->Auth->user('id');
		}

		// Currently Assigned Tasks
		$currentTasks = $this->Task->find('all', [
			'userId' => $userId,
			'conditions' => ['Task.completed' => null],
		]);

		// Your created tasks

		$this->set(compact('currentTasks'));
	}

	public function add() {
		$this->FormData->addData([
			'default' => [
				'Task' => ['creator_id' => $this->Auth->user('id')]
			]
		]);
		$this->render('/Elements/tasks/form');
	}

	public function view($id = null) {
		$this->FormData->findModel($id);
	}

	public function edit($id = null) {
		$this->FormData->editData($id);
		$this->render('/Elements/tasks/form');
	}

	public function delete($id = null) {
		$this->FormData->deleteData($id);
	}
}