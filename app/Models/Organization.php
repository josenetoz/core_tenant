<?php

namespace App\Models;

use Laravel\Cashier\Billable;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Stripe\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organization extends Model
{
    use HasFactory;
    use Billable;

    protected $fillable = [
        'name',
        'document_number',
        'stripe_id',
        'email',
        'phone',
        'slug',
        'trial_ends_at',
    ];


     /**
     * @return BelongsToMany<User, $this>
     */

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class , 'organization_user', 'organization_id', 'user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
