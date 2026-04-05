<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'learning_session_id',
        'author_id',
        'target_id',
        'score',
        'comment',
    ];

    public function learningSession()
    {
        return $this->belongsTo(LearningSession::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}
