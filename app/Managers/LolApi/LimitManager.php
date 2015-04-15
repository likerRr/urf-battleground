<?php namespace URFBattleground\Managers\LolApi;

class LimitManager {

	/** @var Limit[] array */
	private static $limits = [];
	/** @var Limit[] array */
	private static $skippedLimits = [];

	private function __construct() {}

	public static function init(array $limits = []) {
		self::addLimits($limits);
	}

	public static function resetAllCounters()
	{
		foreach (self::$limits as $limit) {
			/** @var Limit $limit */
			$limit->resetCounter();
		}
	}

	/**
	 * @param array $limits
	 * @return $this
	 */
	public static function addLimits(array $limits)
	{
		foreach ($limits as $limit) {
			self::addLimit($limit[0], $limit[1]);
		}
	}

	/**
	 * @param $beats
	 * @param $seconds
	 */
	public static function addLimit($beats, $seconds)
	{
		$beats = (int) $beats;
		$seconds = (int) $seconds;
		if (isset(self::$limits[$seconds])) {
			$currentLimit = self::$limits[$seconds];
			/** @var Limit $currentLimit */
			if ($currentLimit->getBeats() > $beats) {
				self::$limits[$seconds] = new Limit($beats, $seconds);
			} else {
				// skipped limits
				self::$skippedLimits[] = new Limit($beats, $seconds);
			}
		} else {
			self::$limits[$seconds] = new Limit($beats, $seconds);
		}
		ksort(self::$limits);
		self::markIrrelevantLimits();
	}

	/**
	 * @return Limit[]
	 */
	public static function getSkippedLimits()
	{
		return self::$skippedLimits;
	}

	/**
	 * @void
	 */
	private static function markIrrelevantLimits()
	{
		if (!empty(self::$limits)) {
			// the first limit will always be actual
			/** @var Limit $firstLimit */
			$firstLimit = current(self::$limits)->activate();
			$tmpBeats = $firstLimit->getBeats();

			/** @var Limit $next */
			while ($next = next(self::$limits)) {
				if ($next->getBeats() < $tmpBeats) {
					$next->deactivate();
				} else {
					$next->activate();
					$tmpBeats = $next->getBeats();
				}
			}
		}
	}

	public static function isLimitExceed()
	{
		if (empty(self::$limits)) {
			return false;
		}

		$result = false;
		foreach (self::$limits as $limit) {
			/** @var Limit $limit */
			if ($limit->isExceed()) {
				$result = true;
				break;
			}
		}

		return $result;
	}

	public static function nearestAvailableAfterSeconds()
	{
		if (empty(self::$limits)) {
			return false;
		}

		$result = 0;
		foreach (self::$limits as $limit) {
			/** @var Limit $limit */
			$result = $limit->willResetAfterSeconds();
			break;
		}

		return (int) $result;
	}

	public static function tickAll() {
		foreach (self::$limits as $limit) {
			/** @var Limit $limit */
			if (!$limit->tick()) {
				return false;
			}
		}

		return true;
	}

	public static function tickAllOrFail()
	{
		foreach (self::$limits as $limit) {
			/** @var Limit $limit */
			$limit->tickOrFail();
		}

		return true;
	}

	public static function getActualLimits()
	{
		$limits = [];
		foreach (self::$limits as $limit) {
			/** @var Limit $limit */
			if ($limit->isActive()) {
				$limits[] = $limit;
			}
		}

		return $limits;
	}

}
