<ul>
<?php foreach ($teams as $team): ?>
	<h2><?php echo $team['Team']['title']; ?></h2>
	<ul>
	<?php foreach ($team['TeamMember'] as $teamMember): ?>
		<li><?php echo $this->Html->link(
			$teamMember['User']['full_name'],
			['controller' => 'weeks', 'action' => 'view', 'teamMemberId' => $teamMember['id'], 'weekStart' => $weekStart]
		); ?></li>
	<?php endforeach; ?>
	</ul>
<?php endforeach; ?>
</ul>