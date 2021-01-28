<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;


class Advertise extends Model
{
    //
    protected $table = 'advertises';
    
    public $timestamps = false;

    // add 
    // public function add(Request $request)
    // {
    //     $advertise = new Advertise();
    //     $advertise->name = $request->name;
    //     $advertise->summary = $request->summary;
    //     $advertise->description = $request->description;
    //     $advertise->product_id = $request->product_id;
    //     if ($request->hasFile('image')) {
    //         $file = $request->file('image');
    //         $format = $file->getClientOriginalExtension();
    //         if ($format != 'jpg' && $format != 'png' && $format != 'jpeg') {
    //             $errors = new MessageBag(['errorImage' => 'File is not an image!']);
    //             return response()->json([
    //                 'error' => true,
    //                 'message' => $errors,
    //             ]);
    //         }
    //         $name = $file->getClientOriginalName();
    //         $avatar = Str::random(4) . "_" . $name;
    //         while (file_exists("/uploads/advertises-images/" . $avatar)) {
    //             $avatar = Str::random(4) . "_" . $name;
    //         }
    //         $file->move(public_path() . '/uploads/advertises-images/', $avatar);
    //         $advertise->image = $avatar;
    //     } else {
    //         $advertise->image = null;
    //     }
    //     $result = $advertise->save();
    //     return $result;
    // }

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

    public function scopeSearch($query, $request)
    {
        if ($request->has('search') && $request->search != null) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('name', 'LIKE', $request->search . '%')
                ->orWhere('name', 'LIKE', '%' . $request->search);
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
