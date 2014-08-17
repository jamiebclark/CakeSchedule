<?php
App::uses('PluginConfig', 'Scheduler.Utility');

class ScheduleTime {

	const DAY_START 		= '00:00:00';
	const DAY_STOP 			= '23:59:50';
	const MYSQL_DATETIME	= 'Y-m-d H:i:s';
	const MYSQL_DATE 		= 'Y-m-d';
	const MYSQL_TIME 		= 'H:i:s';

	public static function toTime($dateStr = null, $offsetStr = null) {
		if (empty($dateStr)) {
			$dateStr = time();
		}
		if (is_array($dateStr)) {
			$return = [];
			foreach ($dateStr as $k => $str) {
				$return[$k] = self::toTime($str);
			}
			return $return;
		}

		$time = !is_numeric($dateStr) ? strtotime($dateStr) : $dateStr;
		if (!empty($offsetStr)) {
			if (is_numeric($offsetStr)) {
				$time += $offsetStr;
			} else {
				$time = strtotime(date(self::MYSQL_DATETIME, $time) . ' ' . $offsetStr);
			}
		}
		return $time;
	}

	public static function format($format, $date = null, $offset = '') {
		return date($format, self::toTime($date, $offset));
	}

	public static function weekStart($date = null, $offset = 0) {
		$stamp = !empty($date) ? strtotime($date) : time();

		// Invalid passed date
		if (date('Y', $stamp) == 1969) {
			$stamp = time();
		}
		return date(self::MYSQL_DATE, strtotime(sprintf('%s - %d days + %d weeks', 
				date(self::MYSQL_DATE, $stamp), 
				date('w', $stamp),
				$offset
			)));
	}

	public static function date($date = null, $offset = null) {
		return self::format(self::MYSQL_DATE, $date, $offset);
	}

	public static function time($date = null, $offset = null) {
		return self::format(self::MYSQL_TIME, $date, $offset);
	}

	public static function dateTime($date = null, $offset = null) {
		return self::format(self::MYSQL_DATETIME, $date, $offset);
	}



	public static function dateEnd($date = null) {
		return self::format(self::MYSQL_DATE . ' ' . self::DAY_STOP, $date);
	}

	public static function dateStart($date = null) {
		return self::format(self::MYSQL_DATE . ' ' . self::DAY_START, $date);
	}

	public static function startOfWorkDay($date = null) {
		PluginConfig::init('Scheduler');
		return self::format(self::MYSQL_DATE . ' ' . Configure::read('Scheduler.dayStartTime'), $date);
	}


	public static function isMidnight($date = null) {
		return self::format('H:i', $date) == '00:00';
	}
}