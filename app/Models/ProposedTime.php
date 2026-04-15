<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposedTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'exchange_request_id',
        'start_at',
        'duration_minutes',
        'is_selected',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'is_selected' => 'boolean',
    ];

    public function exchangeRequest()
    {
        return $this->belongsTo(ExchangeRequest::class);
    }

    public function getEndAtAttribute()
    {
        return $this->start_at?->copy()->addMinutes($this->duration_minutes);
    }
}
