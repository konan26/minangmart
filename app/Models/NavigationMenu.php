<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationMenu extends Model
{
    protected $fillable = [
        'label',
        'route',
        'active_key',
        'icon',
        'sort_order',
        'is_visible',
        'role_access',
    ];
}
