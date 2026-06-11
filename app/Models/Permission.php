<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\UserPermission;
use App\Models\RolePermission;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'type',
    ];
    protected $hidden = [
        'created_at',
        'deleted_at',
        'updated_at'
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Permission::class, 'parent_id')->with('children');;
    }
    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }
    public static function treeWithChecked(Collection $assignedIds)
    {
        $permissions = self::whereNull('parent_id')
            ->with('children')
            ->get();

        return self::markChecked($permissions, $assignedIds);
    }


    private static function markChecked(object $nodes, object $assignedIds)
    {
        return $nodes->map(function ($node) use ($assignedIds) {

            $node->checked = $assignedIds->contains($node->id);

            if ($node->children->isNotEmpty()) {
                $node->children = self::markChecked(
                    $node->children,
                    $assignedIds
                );
            }

            return $node;
        });
    }
    public function scopeTree(Builder $query): Collection
    {
        return $query->whereNull('parent_id')
            ->with('children')
            ->get();
    }
    public function userPermissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }
    public function rolePermissions(): HasMany
    {
        return $this->hasMany(RolePermission::class);
    }
}
