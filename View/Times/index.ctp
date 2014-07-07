<div class="times-archive">
	<h2 class="times-archive-title"><?php echo $start; ?> - <?php echo $stop; ?></h2>
	<ul class="times-archive-list">
	<?php foreach ($times as $day => $dayTimes): ?>
		<li>
			<?php echo $this->Html->link(
				date('l', strtotime($day)),
				['action' => 'add', 'started' => $day]
			); ?>
			<ul>
			<?php foreach ($dayTimes as $time): ?>
				<li><?php echo $this->Html->link(
					$this->Calendar->dateRange($time['Time']['started'], $time['Time']['stopped'], ['time' => true]),
					['action' => 'edit', $time['Time']['id']],
					['escape' => false]
				);?></li>
			<?php endforeach; ?>
			</ul>
		</li>
	<?php endforeach; ?>
	</ul>
</div>