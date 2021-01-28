<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    //
    protected $table = 'products';
    public $timestamps = false;

    // relationship
    public function producer(){
        return $this->belongsTo('App\Producer');
    }

    public function tag_product() {
        return $this->hasOne('App\TagProduct');
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
                    $query->orderBy('price','asc');
                break;
                case 3:
                    $query->orderBy('rating','desc');
                break;
                case 4:
                    $query->select('id','name','price','description','image','is_actived','is_deleted',DB::raw('quantity-remaining as leftover'))->orderByDesc('leftover');
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

    public function scopeDate($query, $request)
    {
        if ($request->has('date_from') && !$request->has('date_to')) {
            $query->whereDate('create_date', '>', $request->date_from);
        } elseif ($request->has('date_to') && !$request->has('date_from')) {
            $query->whereDate('create_date', '<', $request->date_to);
        } elseif ($request->has('date_from') && ($request->has('date_to'))) {
            $query->whereBetween('create_date', [$request->date_from, $request->date_to]);
        }
        return $query;
    }

    public function scopePrice($query, $request)
    {
        $price_from = $request->price_from;
        $price_to = $request->price_to;
        if ($price_from != null && $price_to == null) {
            $query->where('price', '>', $price_from);
        }
        if ($price_from == null && $price_to != null) {
            $query->where('price', '<', $price_to);
        }
        if ($price_from != null && $price_to != null) {
            $query->whereBetween('price', [$price_from, $price_to]);
        }
        // if ($request->has('price_from')  != null && !$request->has('price_to')) {
        //     $query->where('price', '>', $request->price_from);
        // } elseif ($request->has('price_to') && !$request->has('price_from')) {
        //     $query->where('price', '<', $request->price_to);
        // } elseif ($request->has('price_from')  && ($request->has('price_to'))) {
        //     
        // }
        return $query;
    }

    public function scopeRemaining($query, $request)
    {
        if ($request->has('remaining') && $request->remaining != null) {
            $query->where('remaining', $request->remaining);
        }
        return $query;
    }

    public function scopeCategory($query, $request)
    {
        if ($request->has('category_id') && $request->category_id != null) {
            $query->where('category_id', $request->category_id);
        }
        return $query;
    }

    public function scopeMassCategory($query, $request)
    {
        if ($request->has('category_id_list') && $request->category_id_list != null) {
            $query->whereIn('category_id', $request->category_id_list);
        }
        return $query;
    }

    public function scopeProducer($query, $request)
    {
        if ($request->has('producer_id') && $request->producer_id != null) {
            $query->where('producer_id', $request->producer_id);
        }
        return $query;
    }

    public function scopeMassProducer($query, $request)
    {
        if ($request->has('producer_id_list') && $request->producer_id_list != null) {
            $query->whereIn('producer_id', $request->producer_id_list);
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
