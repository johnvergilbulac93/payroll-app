<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{
    protected $fillable = [
        'user_id',
        'permission_id'
    ];
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
