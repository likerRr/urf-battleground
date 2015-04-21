<?php

if (!function_exists('object_get')) {
	/**
	 * Get an item from an object using "dot" notation.
	 *
	 * @param  object  $object
	 * @param  string  $key
	 * @param  mixed   $default
	 * @return mixed
	 */
	function object_get($object, $key, $default = null)
	{
		if (is_null($key) || trim($key) == '') return $object;

		foreach (explode('.', $key) as $segment)
		{
			if ( ! is_object($object) || ! isset($object->{$segment}))
			{
				return value($default);
			}

			$object = $object->{$segment};
		}

		return $object;
	}
}

if (!function_exists('array_get')) {
	/**
	 * Get an item from an array using "dot" notation.
	 * @param $array
	 * @param $key
	 * @param $default
	 * @return mixed
	 */
	function array_get($array, $key, $default = null)
	{
		if (is_null($key)) return $array;

		if (isset($array[$key])) return $array[$key];

		foreach (explode('.', $key) as $segment)
		{
			if ( ! is_array($array) || ! array_key_exists($segment, $array))
			{
				return value($default);
			}

			$array = $array[$segment];
		}

		return $array;
	}
}

if (!function_exists('eachVal')) {
	/**
	 * @param $arr
	 * @param $onValue
	 */
	function eachVal(array $arr, callable $onValue)
	{
		$arr = (array)$arr;
		foreach ($arr as $val) {
			if (is_callable($onValue)) {
				$onValue($val);
			}
		}

	}
}

if (!function_exists('getVal')) {
	/**
	 * Get an item from an array or an object using "dot" notation.
	 * @param $subj
	 * @param $key
	 * @param null $default
	 * @return mixed
	 */
	function getVal($subj, $key, $default = null)
	{
		if (is_object($subj)) {
			return object_get($subj, $key, $default);
		}

		return array_get($subj, $key, $default);
	}

}
