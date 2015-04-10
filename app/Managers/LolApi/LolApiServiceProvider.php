<?php namespace URFBattleground\Managers\LolApi;

use Illuminate\Support\ServiceProvider;

class LolApiServiceProvider extends ServiceProvider {

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('URFBattleground\Managers\LolApi\LolApi', function() {
			$lolApi = new LolApi();

			return $lolApi;
		});
	}

}
