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

	public static function logException(\Exception $e, $data = []) {
		$generalData = [
			'message' => $e->getMessage(),
			'code' => $e->getCode(),
			'file' => $e->getFile(),
			'line' => $e->getLine(),
//			'trace' => $e->getTraceAsString()
		];

		\Log::critical($e->getMessage(), array_merge($generalData, $data));
	}

}
