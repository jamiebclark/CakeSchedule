<?php
class SchedulerAppController extends AppController {
	public $helpers = [
		//'Html', 
		//'Form', 
		'Layout.Calendar'
	];

	public $layout = 'cake_schedule_default';

	public function beforeRender() {
		$this->set('scheduleUserId', $this->Auth->user('id'));
		return parent::beforeRender();
	}
}