<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagProduct extends Model
{
    //
    protected $table = 'tag_products';
    public $timestamps = false;
    
    public function product(){
        return $this->belongsTo('App\Product');
    }
}
