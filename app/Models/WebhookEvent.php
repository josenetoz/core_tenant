<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebhookEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'payload',
        'status',
        'received_at',
    ];


}
