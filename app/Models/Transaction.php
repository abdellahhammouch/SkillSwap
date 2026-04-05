<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'learning_session_id',
        'user_id',
        'type',
        'amount_minutes',
        'description',
    ];

    public function learningSession()
    {
        return $this->belongsTo(LearningSession::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
