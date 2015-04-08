<?php namespace URFBattleground\Managers\RiotApi;

use Illuminate\Support\ServiceProvider;

class RiotApiServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('URFBattleground\Managers\RiotApi\Contracts\RiotApi', function($app) {
			$riotApi = $app->make('URFBattleground\Managers\RiotApi\RiotApi');

			return $riotApi;
		});

		$this->app->instance('riotapi', $this->app['URFBattleground\Managers\RiotApi\Contracts\RiotApi']);
	}

}
