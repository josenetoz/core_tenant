<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Enums\TenantSuport\TicketTypeEnum;

use Illuminate\Testing\Fluent\Concerns\Has;
use App\Enums\TenantSuport\TicketStatusEnum;
use App\Enums\TenantSuport\TicketPriorityEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ticket extends Model
{
    use HasFactory;
    
    protected $fillable = [
                'organization_id',
                'user_id',
                'title', 
                'description', 
                'file',
                'image_path', 
                'priority',
                'status',
                'type',
                'closed_at'
            ];

    protected $casts = [

        'type' => TicketTypeEnum::class,
        'priority' => TicketPriorityEnum::class,
        'status' => TicketStatusEnum::class,
        'file' => 'array',
        'created_at' => 'datetime',
        'closed_at' => 'datetime', 
      
        
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticket_responses(): HasMany
    {
        return $this->hasMany(TicketResponse::class);
    }
}