<?php namespace URFBattleground\Managers\LolApi\Api\Response\Dto;

trait DtoUtilityTrait {

	protected function each($arr, $onValue)
	{
		$arr = (array) $arr;
		foreach ($arr as $val) {
			if (is_callable($onValue)) {
				$onValue($val);
			}
		}

	}

	protected function getVal($subj, $key, $default = null)
	{
		if (is_object($subj)) {
			return object_get($subj, $key, $default);
		}

		return array_get($subj, $key, $default);
	}

}
