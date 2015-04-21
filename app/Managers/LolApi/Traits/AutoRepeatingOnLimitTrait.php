<?php namespace URFBattleground\Managers\LolApi\Traits;

trait AutoRepeatingOnLimitTrait {

	/** @var bool */
	private $autoRepeat = false;
	/** @var int */
	private $repeatAttempts;

	/**
	 * @param bool|int $attempts - int to set repeat attempts or empty to make requests until limit passes
	 * @return $this
	 */
	public function autoRepeatOnLimitExceed($attempts = null)
	{
		$this->autoRepeat = true;
		$this->repeatAttempts = (is_int($attempts) ? $attempts : null);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function throwOnLimitExceed()
	{
		$this->autoRepeat = false;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isAutoRepeat()
	{
		return $this->autoRepeat;
	}

	/**
	 * @return bool
	 */
	public function isRepeatUntilNotPass()
	{
		return empty($this->repeatAttempts) && $this->autoRepeat;
	}

	/**
	 * @return int
	 */
	public function getRepeatAttempts()
	{
		return $this->repeatAttempts;
	}

}
