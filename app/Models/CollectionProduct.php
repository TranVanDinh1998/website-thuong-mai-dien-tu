<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionProduct extends Model
{
    //
    protected $table = 'collection_products';
    public $timestamps = false;
    
    public function product() {
        return $this->belongsTo('App\Product');
    }
}
