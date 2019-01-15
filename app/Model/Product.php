<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=['name','detail','price','stock','discount'];
    protected $with=['reviews'];

    public function reviews(){
        return $this->hasMany(Review::class);
    }
}
