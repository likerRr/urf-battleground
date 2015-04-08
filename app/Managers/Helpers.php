<?php namespace URFBattleground\Managers;

class Helpers {

	/**
	 * Returns $parameters (if it's an array) as is or null
	 * @param $parameters
	 * @return null
	 */
	public static function nullOrArray($parameters) {
		if (!is_array($parameters)) {
			return null;
		}

		return $parameters;
	}

}
