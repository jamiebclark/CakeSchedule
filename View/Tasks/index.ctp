<h2>Tasks</h2>
<?php echo $this->Html->link('Add Task', ['action' => 'add']); ?>
<ul class="tasks-archive">
	<?php foreach ($currentTasks as $task): ?>
		<li><?php echo $this->Html->link(
			$task['Task']['title'],
			['controller' => 'tasks', 'action' => 'view', $task['Task']['id'], 'plugin' => 'cake_schedule']
		); ?></li>
	<?php endforeach; ?>
</ul>
