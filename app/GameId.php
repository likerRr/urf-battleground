<?php namespace URFBattleground;

use Illuminate\Database\Eloquent\Model;

class GameId extends Model {

	protected $table = 'games_ids';

	protected $fillable = ['game_id', 'region_id', 'receive_at'];

//	protected $hidden = [];

}
