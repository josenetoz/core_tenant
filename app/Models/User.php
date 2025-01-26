<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements FilamentUser, HasTenants, HasAvatar, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'avatar_url',
        'settings',
        'is_tenant_admin',
        'email',
        'phone',
        'password',
        'is_active',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'settings' => 'array',
            'is_active' => 'boolean',
            'is_tenant_admin' => 'boolean',
        ];
    }
/**
     * @return BelongsToMany<Organizations, $this>
     */


     public function organizations(): BelongsToMany
     {
         return $this->belongsToMany(Organization::class);
     }
     public function getTenants(Panel $panel): Collection
     {
         return $this->organizations;
     }

     public function canAccessTenant(Model $organization): bool
     {
         return $this->organizations()->whereKey($organization)->exists();
     }
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url
        ? Storage::url($this->avatar_url)
        : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=128&background=000000&color=ffffff'; // URL do avatar gerado
    }

    public function organization(): BelongsToMany
     {
         return $this->belongsToMany(Organization::class);
     }



}
