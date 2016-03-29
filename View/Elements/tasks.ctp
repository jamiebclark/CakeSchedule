<?php
$this->Html->script('Scheduler.script', ['inline' => false]);

if (!isset($tasks) && $this->Html->value('Task')) {
	$tasks = $this->Html->value('Task');
}
$taskCount = count($tasks) + 1;
?>

<style type="text/css">
.scheduler-tasks.loading {
	background-color: #EEE;
	color: #CCC;
}
.scheduler-tasks .alert {
	position: absolute;
	bottom: 0;
	right: 0;
}
.scheduler-tasks-list-item-secondary {
	display: none;
	margin-left: 12px;
	margin-top: 10px;
}
.active .scheduler-tasks-list-item-secondary {
	display: block;
}
.completed .scheduler-tasks-list-item-secondary {
	display: none;
}

.scheduler-tasks-list-item-options, .scheduler-tasks-list-item-options li {
	margin: 0;
	padding: 0;
	list-style-type: none;
}
.scheduler-tasks-list-item {
	padding: 5px 10px;
}
.scheduler-tasks-list-item.active {
	background-color: yellow;
}
.scheduler-tasks-list-item.completed {
	background-color: #EEE;
}

.scheduler-tasks-list-item-input {
	resize: none;
}
.completed .scheduler-tasks-list-item-input:focus {
	border-color: transparent;
	box-shadow: 0 0 0;
}
.completed .scheduler-tasks-list-item-input {
	text-decoration: line-through;
	background-color: transparent;
	border-color: transparent;
}

</style>

<?php echo $this->Form->create('Task', [
	'url' => ['action' => 'add'], 
	'class' => 'scheduler-tasks'
]); ?>
<div class="panel panel-default">
	<div class="panel-heading"><span class="panel-title">Tasks</span></div>
	<div class="panel-body">
		<div class="scheduler-tasks-list">
			<?php for ($i = 0; $i < $taskCount; $i++): ?>
				<div class="scheduler-tasks-list-item">
					<?php echo $this->Form->hidden("Task.$i.id"); ?>
					<?php echo $this->Form->hidden("Task.$i.creator_id", ['default' => $scheduleUserId]); ?>
					<div class="scheduler-tasks-list-item-primary">
						<div class="input-group">
							<div class="input-group-btn"><?php 
								echo $this->Form->input("Task.$i.completed", [
									'value' => ScheduleTime::dateTime(),
									'div' => false,
									'label' => false,
									'type' => 'checkbox',
									'tabindex' => -1,
								]); 
							?></div>
							<?php
								echo $this->Form->input("Task.$i.title", [
									'type' => 'textarea',
									'rows' => 1,
									'label' => false,
									'div' => false,
									'class' => 'form-control scheduler-tasks-list-item-input'
								]);
						?></div>
					</div>
					<div class="scheduler-tasks-list-item-secondary">
						<ul class="scheduler-tasks-list-item-options">
							<li>
								<h5><i class="fa fa-clock-o"></i> Deadline</h5>
								<?php echo $this->FormLayout->input("Task.$i.deadline", ['type' => 'date', 'label' => false]); ?>
							</li>
							<li>
								<h5><i class="fa fa-clipboard"></i> Project</h5>
								<?php echo $this->Form->input("Task.$i.team_project_id"); ?>
								<?php echo $this->Form->input("Task.$i.team_project_new"); ?>
							</li>
							<li>
								<h5><i class="fa fa-user"></i> User</h5>
								<?php echo $this->Form->input("Task.$i.schedule_user_id", [
									'default' => $scheduleUserId,
									'label' => false,
									'after' => '<span class="help-block">Assign this task to another team member</span>'
								]); ?>
							</li>
						</ul>
					</div>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</div>
<?php echo $this->Form->end('Save'); ?>