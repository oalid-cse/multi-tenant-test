<?php

namespace App\Models;

use App\Trait\TenantConnection;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use TenantConnection;

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
