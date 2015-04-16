<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesIdsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('regions', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->string('name');
		});
		Schema::create('games_ids', function (Blueprint  $table) {
			$table->increments('id');
			$table->bigInteger('game_id')->unique();
			$table->smallInteger('region_id')->references('id')->on('regions');
			$table->integer('receive_at');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('games_ids', function(Blueprint $table) {
			$table->dropForeign('regions_region_id_foreign');
		});
		Schema::drop('games_ids');
		Schema::drop('regions');
	}

}
