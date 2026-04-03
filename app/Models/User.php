<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'bio',
        'city',
        'avatar',
        'credit_balance_minutes',
        'reputation_score',
        'account_status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function needs()
    {
        return $this->hasMany(Need::class);
    }

    public function sentExchangeRequests()
    {
        return $this->hasMany(ExchangeRequest::class, 'learner_id');
    }

    public function receivedExchangeRequests()
    {
        return $this->hasMany(ExchangeRequest::class, 'helper_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function authoredRatings()
    {
        return $this->hasMany(Rating::class, 'author_id');
    }

    public function receivedRatings()
    {
        return $this->hasMany(Rating::class, 'target_id');
    }

    public function learnerSessions()
    {
        return $this->hasManyThrough(
            LearningSession::class,
            ExchangeRequest::class,
            'learner_id',
            'exchange_request_id'
        );
    }

    public function helperSessions()
    {
        return $this->hasManyThrough(
            LearningSession::class,
            ExchangeRequest::class,
            'helper_id',
            'exchange_request_id'
        );
    }
}
