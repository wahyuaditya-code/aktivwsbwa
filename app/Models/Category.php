<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\HasMany;


class Category extends Model
{
    use HasFactory, softDeletes;
    protected $fillable= [
        'name',
        'slug',
        'tagline',
        'icon',

    ];
    public function setNameAttribute($value){
        $this->attributes['name']=$value;
        $this->attributes['slug']=Str::slug($value);
    }

    public function workshops(): HasMany{
        return $this->hasMany(Workshop::class); //untuk menampilkan data workshop di category
    }
}
