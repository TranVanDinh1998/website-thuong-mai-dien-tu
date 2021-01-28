<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagProduct extends Model
{
    //
    protected $table = 'tag_products';
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    // filter
    public function scopeSortId($query, $request)
    {
        if ($request->has('sort_id') && $request->sort_id != null) {
            switch ($request->sort_id) {
                case 0:
                    $query->orderBy('id', 'asc');
                    break;
                case 1:
                    $query->orderBy('id', 'desc');
                    break;
            }
        }
        return $query;
    }

    public function scopeStatus($query, $request)
    {
        if ($request->has('status') && $request->status != null) {
            switch ($request->status) {
                case 0:
                    $query->where('is_actived', 0);
                    break;
                case 1:
                    $query->where('is_actived', 1);
                    break;
            }
        }
        return $query;
    }

    public function scopeActive($query)
    {
        $query->where('is_actived', 1);
        return $query;
    }
    public function scopeInactive($query)
    {
        $query->where('is_actived', 0);
        return $query;
    }

    public function scopeSoftDelete($query)
    {
        $query->where('is_deleted', 1);
        return $query;
    }
    public function scopeNotDelete($query)
    {
        $query->where('is_deleted', 0);
        return $query;
    }
}
