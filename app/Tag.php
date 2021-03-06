<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $table = 'tags';
    public $timestamps = false;

    public function tag_products()
    {
        return $this->hasMany('App\TagProduct');
    }

    public function scopeId($query, $request)
    {
        if ($request->has('tag_id')) {
            $query->find($request->tag_id);
        }
        return $query;
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

    public function scopeSort($query,$request) {
        if ($request->has('sort')){
            switch($request->sort) {
                case 0:
                    $query->orderBy('id','asc');
                break;
                case 1:
                    $query->orderBy('name','asc');
                break;
                case 2:
                    $query->orderBy('view','desc');
                break;
            }
        }
        return $query;
    }

    public function scopeSearch($query, $request)
    {
        if ($request->has('search') && $request->search != null) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('name', 'LIKE', $request->search . '%')
                ->orWhere('name', 'LIKE', '%' . $request->search);
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
