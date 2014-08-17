<?php
class TeamProjectsController extends SchedulerAppController {
	public $name = 'TeamProjects';
	public $helpers = ['Layout.CollapseList'];

	public function index() {
		$teamProjects = $this->TeamProject->find('threaded');
		$this->set(compact('teamProjects'));
	}

	public function add($parentId = null) {
		$this->FormData->addData([
			'default' => [
				'Team' => ['parent_id' => $parentId]
			]
		]);
		$this->render('/Elements/team_projects/form');
	}

	public function view($id = null) {
		$this->TeamProject->recover();
		$this->FormData->findModel($id);
	}

	public function edit($id = null) {
		$this->FormData->editData($id);
		$this->render('/Elements/team_projects/form');
	}

	public function delete($id = null) {
		$this->FormData->deleteData($id);
	}

/*
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
*/
	public function _setFormElements() {
		$userId = $this->Auth->user('id');
		$teams = ['' => ' --- '] + $this->TeamProject->ScheduleUser->findTeams($userId, 'list');

		$teamProjects = ['' => ' --- '] + $this->TeamProject->find('slash');
		$this->set(compact('teams', 'teamProjects'));
	}
}