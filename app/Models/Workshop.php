<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;


class Workshop extends Model
{
    use HasFactory, softDeletes;
    
    protected $fillable=[
        'name',
        'slug',
        'thumbnail',
        'venue_thumbnail',
        'bg_map',
        'address',
        'about',
        'is_open',
        'has_started', 
        'started_at',
        'workshop_instructor_id',
        'category_id',
        'time_at',
        'price',
        //WAJIB SAMA SEPERTI NAMA DI DATABASE

    ];

    protected $casts= //untuk mengubah string menjadi date
    [
        'started_at' =>'date',
        'time_at' => 'datetime:H:i',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name']= $value; //belajar laravel 11 bwa
        $this->attributes['slug'] = Str::slug($value); //belajar-laravel-11-bwa
    }

    public function benefits(): HasMany //many to many
    {
        return $this->hasMany(WorkshopBenefit::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(WorkshopParticipant::class);
    }

    public function category(): BelongsTo //one to many
    {
        return $this->BelongsTo(category::class, 'category_id');
    }
    public function instructor(): BelongsTo
    {
        return $this->BelongsTo(WorkshopInstructor::class, 'workshop_instructor_id');
    }

}


