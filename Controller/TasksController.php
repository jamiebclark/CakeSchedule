<?php
class TasksController extends SchedulerAppController {
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

	public function save() {
		if (!empty($this->request->data['Task'])) {
			foreach ($this->request->data['Task'] as $k => $task) {
				if (empty($task['title']) && empty($task['id'])) {
					unset($this->request->data['Task'][$k]);
				}
			}
			$this->request->data['Task'] = array_values($this->request->data['Task']);
		}
		$this->FormData->setSuccessRedirect(['action' => 'index']);
		$this->FormData->saveData();

		$this->redirect(['action' => 'index']);
	}

}