<?php
class ScheduleTime {
	public static function weekStart($date = null, $offset = 0) {
		$stamp = !empty($date) ? strtotime($date) : time();

		// Invalid passed date
		if (date('Y', $stamp) == 1969) {
			$stamp = time();
		}
		return date('Y-m-d', strtotime(sprintf('%s - %d days + %d weeks', 
				date('Y-m-d', $stamp), 
				date('w', $stamp),
				$offset
			)));
	}
}