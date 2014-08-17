<?php
$prefix = 'Tasks';
if (isset($count)) {
	$prefix .= '.' . $count;
}
echo $this->Form->hidden("$prefix.id");
?>
<div class="row">

	<div class="input-group">
		<div class="input-group-btn"><?php 
			echo $this->Form->input("$prefix.completed", [
				'value' => ScheduleTime::dateTime(),
				'div' => false,
				'label' => false,
				'type' => 'checkbox',
			]); 
		?></div>
	<?php
		echo $this->Form->input("$prefix.title", [
			'label' => false,
			'div' => false,
		]);
	?></div>
</div>
