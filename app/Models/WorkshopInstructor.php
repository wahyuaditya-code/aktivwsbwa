<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\ELoquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkshopInstructor extends Model
{
    use HasFactory, softDeletes;

    protected $fillable=[
        'name',
        'avatar',
        'ocupation',

    ];

    public function workshop(): HasMany{
        return $this->hasMany(workshop::class);
    }
}
