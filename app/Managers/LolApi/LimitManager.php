<?php namespace URFBattleground\Managers\LolApi;

class LimitManager {

	/** @var Limit[] array */
	private $limits = [];
	/** @var Limit[] array */
	private $skippedLimits = [];

	public function __construct(array $limits = [])
	{
		$this->addLimits($limits);
	}

	/**
	 * @param array $limits
	 * @return $this
	 */
	public function addLimits(array $limits)
	{
		foreach ($limits as $limit) {
			$this->addLimit($limit[0], $limit[1]);
		}

		return $this;
	}

	/**
	 * @param $beats
	 * @param $seconds
	 */
	public function addLimit($beats, $seconds)
	{
		$beats = (int) $beats;
		$seconds = (int) $seconds;
		if (isset($this->limits[$seconds])) {
			$currentLimit = $this->limits[$seconds];
			/** @var Limit $currentLimit */
			if ($currentLimit->getBeats() > $beats) {
				$this->limits[$seconds] = new Limit($beats, $seconds);
			} else {
				// skipped limits
				$this->skippedLimits[] = new Limit($beats, $seconds);
			}
		} else {
			$this->limits[$seconds] = new Limit($beats, $seconds);
		}
		ksort($this->limits);
		$this->markIrrelevantLimits();
	}

	/**
	 * @return Limit[]
	 */
	public function getSkippedLimits()
	{
		return $this->skippedLimits;
	}

	/**
	 * @void
	 */
	private function markIrrelevantLimits()
	{
		if (!empty($this->limits)) {
			// the first limit will always be actual
			/** @var Limit $firstLimit */
			$firstLimit = current($this->limits)->setActual(true);
			$tmpBeats = $firstLimit->getBeats();

			/** @var Limit $next */
			while ($next = next($this->limits)) {
				if ($next->getBeats() < $tmpBeats) {
					$next->setActual(false);
				} else {
					$next->setActual(true);
					$tmpBeats = $next->getBeats();
				}
			}
		}
	}

}
