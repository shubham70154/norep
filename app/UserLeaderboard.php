<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLeaderboard extends Model
{
    protected $table = 'user_leaderboards';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'user_id',
        'event_id',
        'sub_event_id',
        'referee_id',
        'event_creator_id',
        'score_given_by',
        'header',
        'scoreboard',
        'total_points',
        'is_final_submit',
        'referee_signature_url',
        'athlete_signature_url',
        'created_at',
        'updated_at'
    ];
}
