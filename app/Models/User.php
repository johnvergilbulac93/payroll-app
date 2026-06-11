<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasUlids, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'isActive',
        'status',
        'role',
        'username'
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function uniqueIds(): array
    {
        return ['public_id'];
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'isActive' => 'boolean',
        ];
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    public static function findByPublicId(string $id): self
    {
        return static::where('public_id', '=', $id)->firstOrFail();
    }
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        });
    }
}
