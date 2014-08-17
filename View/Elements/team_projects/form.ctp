<?php 
echo $this->Form->create(); 
echo $this->Form->hidden('id');
echo $this->Form->hidden('creator_id');
echo $this->Form->inputs([
	'parent_id' => ['options' => $teamProjects],
	'team_id',
	'title',
	'description',
	'completed' => [
		'label' => 'Completed',
		'helpBlock' => 'Is the Project done?',
	],
	'fieldset' => false,
]);
echo $this->Form->end('Update'); 
?>