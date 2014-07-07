<?php
class TeamsController extends CakeScheduleAppController {
	public $name = 'Teams';

	public function admin_index() {
		$this->set('teams', $this->paginate());
	}

	public function admin_add() {
		$this->FormData->addData();
	}

	public function admin_view($id = null) {
		$this->FormData->findModel($id);
	}

	public function admin_edit($id = null) {
		$this->FormData->editData($id);
	}

	public function admin_delete($id = null) {
		$this->FormData->deleteData($id);
	}
}