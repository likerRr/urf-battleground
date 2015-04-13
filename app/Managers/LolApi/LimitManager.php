<?php namespace URFBattleground\Managers\LolApi;

class LimitManager {

	private $limits = [];

	public function __construct(array $limits = [])
	{
		$this->addLimits($limits);
	}

	public function addLimits(array $limits)
	{
		foreach ($limits as $limit) {
			$this->addLimit($limit[0], $limit[1]);
		}

		return $this;
	}

	public function addLimit($beats, $seconds)
	{
		$beats = (int) $beats;
		$seconds = (int) $seconds;
		$this->limits[] = new Limit($beats, $seconds);
		$this->sortLimitsAsc();
	}

	private function sortLimitsAsc()
	{
		usort($this->limits, function($limitOne, $limitTwo) {
			/** @var Limit $limitOne */
			/** @var Limit $limitTwo */
			return $limitOne->getSeconds() >= $limitTwo->getSeconds() &&
				$limitOne->getBeats() > $limitTwo->getBeats();
		});

		var_dump($this->limits);
	}

}
