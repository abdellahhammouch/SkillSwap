<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
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
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::get(fn () => trim($this->first_name.' '.$this->last_name));
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    public function needs(): HasMany
    {
        return $this->hasMany(Need::class);
    }

    public function sentExchangeRequests(): HasMany
    {
        return $this->hasMany(ExchangeRequest::class, 'learner_id');
    }

    public function receivedExchangeRequests(): HasMany
    {
        return $this->hasMany(ExchangeRequest::class, 'helper_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function authoredRatings(): HasMany
    {
        return $this->hasMany(Rating::class, 'author_id');
    }

    public function receivedRatings(): HasMany
    {
        return $this->hasMany(Rating::class, 'target_id');
    }

    public function learnerSessions(): HasManyThrough
    {
        return $this->hasManyThrough(
            LearningSession::class,
            ExchangeRequest::class,
            'learner_id',
            'exchange_request_id'
        );
    }

    public function helperSessions(): HasManyThrough
    {
        return $this->hasManyThrough(
            LearningSession::class,
            ExchangeRequest::class,
            'helper_id',
            'exchange_request_id'
        );
    }
}
