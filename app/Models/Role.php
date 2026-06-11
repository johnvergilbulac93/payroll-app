<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes, HasUlids;

    protected $fillable = [
        'Description',
        'isActive'
    ];

    protected function casts(): array
    {
        return [
            'isActive' => 'boolean',
        ];
    }
    public static function findByPublicId(string $id): self
    {
        return static::where('public_id', '=', $id)->firstOrFail();
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    public function uniqueIds(): array
    {
        return ['public_id'];
    }
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->orWhere('Description', 'like', "%{$search}%");
            });
        });
    }
}
