<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

class Guest extends Model {

    protected $table = "guests";

    protected $fillable = ['last_name', 'first_name', 'nick_name', 'facebook_credentials', 'email', 'suggestion_credit', 'suggestion_timeout', 'guest_priviledge_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['suggestion_timeout'];

    public function votes() {
        return $this->hasMany('App\Vote');
    }

    public function suggestions() {
        return $this->hasMany('App\Suggestion');
    }

    public function activation_codes() {
        return $this->hasMany('App\ActivationCode');
    }

    public function guest_priviledge() {
        return $this->belongsTo('App\GuestPriviledge');
    }

    public function events() {
        return $this->belongsToMany('App\Event', 'activation_codes');
    }

    public static function generateNickName() {
        $faker = Faker::create();

        return  $faker->name();
    }
}
