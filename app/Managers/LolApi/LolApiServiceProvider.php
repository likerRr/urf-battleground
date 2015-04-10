<?php namespace URFBattleground\Managers\LolApi;

use Illuminate\Support\ServiceProvider;

class LolApiServiceProvider extends ServiceProvider {

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
		$this->app->singleton('URFBattleground\Managers\LolApi\LolApiContract', function($app) {
			$lolApi = $app->make('URFBattleground\Managers\LolApi\LolApi');

			return $lolApi;
		});

		$this->app->instance('lolapi', $this->app['URFBattleground\Managers\LolApi\LolApiContract']);
	}

}
