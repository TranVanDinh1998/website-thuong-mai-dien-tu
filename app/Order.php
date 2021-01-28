<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'orders';
    public $timestamps = false;

    public function shipping_address() {
        return $this->hasOne('App\ShippingAddress');
    }
    public function payment() {
        return $this->hasOne('App\Payment');
    }
    public function order_details(){
        return $this->hasMany('App\OrderDetail');
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
        else {
            $query->orderBy('id', 'desc');
        }
        return $query;
    }
    
    public function scopeSortTotal($query, $request)
    {
        if ($request->has('sort_total') && $request->sort_id != null) {
            switch ($request->sort_id) {
                case 0:
                    $query->orderBy('total', 'asc');
                    break;
                case 1:
                    $query->orderBy('total', 'desc');
                    break;
            }
        }
        return $query;
    }

    public function scopeSortDate($query, $request)
    {
        if ($request->has('sort_date') && $request->sort_id != null) {
            switch ($request->sort_id) {
                case 0:
                    $query->orderBy('create_date', 'asc');
                    break;
                case 1:
                    $query->orderBy('create_date', 'desc');
                    break;
            }
        }
        else {
            $query->orderBy('create_date', 'desc');
        }
        return $query;
    }

    public function scopeSortPaid($query, $request)
    {
        if ($request->has('sort_paid') && $request->sort_id != null) {
            switch ($request->sort_id) {
                case 0:
                    $query->where('is_paid',0);
                    break;
                case 1:
                    $query->where('is_paid', 1);
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
                    $query->where('status', 0);
                    break;
                case 1:
                    $query->where('status', 1);
                    break;
                case 2:
                    $query->where('status', 2);
                    break;
                case 3:
                    $query->where('status', 3);
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

    public function scopePay($query)
    {
        $query->where('is_paid', 1);
        return $query;
    }

    public function scopeNotPay($query)
    {
        $query->where('is_paid', 0);
        return $query;
    }

    public function scopeDone($query)
    {
        $query->where('is_done', 1);
        return $query;
    }

    public function scopeNotDone($query)
    {
        $query->where('is_done', 0);
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
