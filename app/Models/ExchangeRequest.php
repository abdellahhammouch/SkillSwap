<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExchangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'learner_id',
        'helper_id',
        'need_id',
        'skill_id',
        'message',
        'duration_minutes',
        'status',
        'expires_at',
        'accepted_at',
        'cancelled_at',
        'completed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function helper()
    {
        return $this->belongsTo(User::class, 'helper_id');
    }

    public function need()
    {
        return $this->belongsTo(Need::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function proposedTimes()
    {
        return $this->hasMany(ProposedTime::class);
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    public function learningSession()
    {
        return $this->hasOne(LearningSession::class);
    }
}
