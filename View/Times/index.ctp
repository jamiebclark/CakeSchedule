<?php
echo $this->Layout->neighbors(
		['Prev', [$userId, $prevStart, $prevStop]], 
		['Next', [$userId, $nextStart, $nextStop]]
	);
?>
<div class="row">
	<div class="col-md-8">
		<div class="times-archive">
			<h2 class="times-archive-title">
				<?php echo ScheduleTime::format('F j', $start); ?> - 
				<?php echo ScheduleTime::format('F j', $stop); ?>
			</h2>
			<?php echo $this->Html->link('Add Time', ['action' => 'add', 'started' => $nextOpenTime]); ?>
			<ul class="times-archive-list nav nav-pills nav-stacked">
			<?php 
			list($m,$d,$y) = explode('-', date('n-j-Y', $startStamp));
			while(($stamp = mktime(0,0,0,$m,$d,$y)) <= $stopStamp): 
				$day = ScheduleTime::date($stamp);
				?>
				<li>
					<h4 class="times-archive-list-title">
					<?php echo $this->Html->link(
						date('l', strtotime($day)),
						['action' => 'add', 'started' => $day]
					); ?>
						<span class="label label-default pull-right">
							<?php echo date('n/j', $stamp); ?>
						</span>
					</h4>
					<?php if (!empty($times[$day])):
						$dayTimes = $times[$day];
						$lastTime = null;
						?>
						<ul class="nav nav-pills nav-stacked">
						<?php foreach ($dayTimes as $time): 
							$isActive = !empty($activeTime) && $activeTime['Time']['id'] == $time['Time']['id'];
							
							if (!empty($lastTime) && $time['Time']['started'] != $lastTime): ?>
								<li class="empty">
									<?php echo $this->Html->link('Empty', ['action' => 'add', 'started' => $lastTime]); ?>
								</li>
							<?php endif;

							echo $this->Html->tag('li', 
								$this->Html->link(
									$this->Calendar->dateRange($time['Time']['started'], $time['Time']['stopped'], ['time' => true]),
									['action' => 'edit', $time['Time']['id']],
									['escape' => false]
								),
								['class' => $isActive ? 'active' : null]
							);

							$lastTime = $time['Time']['stopped'];
						endforeach; ?>
						<li class="empty">
							<?php echo $this->Html->link('Empty', ['action' => 'add', 'started' => $lastTime]); ?>
						</li>
						</ul>
					<?php endif; ?>
				</li>
				<?php $d++; ?>
			<?php endwhile; ?>
			</ul>
		</div>
	</div>
	<div class="col-md-4">
		<?php echo $this->element('Scheduler.tasks'); ?>
	</div>
</div>