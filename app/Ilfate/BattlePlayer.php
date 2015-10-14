<?php

namespace Ilfate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class BattlePlayer extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'battle_players';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array();

    protected $appends = array();

    protected $guarded = array();



}
