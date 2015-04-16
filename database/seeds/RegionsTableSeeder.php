<?php

use Illuminate\Database\Seeder;
use URFBattleground\Region;

class RegionsTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('regions')->delete();
		$regions = [
			'br' => 'Brazil',
			'eune' => 'EU Nordic & East',
			'euw' => 'EU West',
			'kr' => 'Korea',
			'lan' => 'Latin America North',
			'las' => 'Latin America South',
			'na' => 'North America',
			'oce' => 'Oceania',
			'tr' => 'Turkey',
			'ru' => 'Russia',
			'pbe' => 'Public Beta Environment',
		];
		foreach ($regions as $name => $title) {
			Region::create([
				'name' => $name,
				'title' => $title,
			]);
		}
	}

}