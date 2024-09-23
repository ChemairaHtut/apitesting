<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['name'];
    protected $dates = ['deleted_at'];
    public function products(){
        return $this->hasMany(Product::class);
    }
    public function image(){
        return $this->morphOne(Image::class,'imageable');
    }
}
