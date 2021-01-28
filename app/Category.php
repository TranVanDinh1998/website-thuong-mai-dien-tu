<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;


class Category extends Model
{
    //
    protected $table = 'categories';
    public $timestamps = false;

    // construct
    public function __construct()
    {
        
    }

    public static function add($request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->image = null;
        $category->is_actived = 1;
        $category->is_deleted = 0;
        $result = $category->save();
        return $result;
    }

    public static function addImage($id,$request)
    {
        $category = Category::find($id);
        $path = public_path('uploads/categories-images/' . $category->id);
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = $file->getClientOriginalName();
            $avatar = Str::random(4) . "_" . $name;
            while (file_exists("/uploads/categories-images/" . $category->id . "/" . $avatar)) {
                $avatar = Str::random(4) . "_" . $name;
            }
            $file->move(public_path() . '/uploads/categories-images/' . $category->id, $avatar);
            $category->image = $avatar;
        }
        $result = $category->save();
        return $result;
    }

    // relationship
    public function products()
    {
        return $this->hasMany('App\Product');
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

    public function scopeSort($query, $request)
    {
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 0:
                    $query->orderBy('id', 'asc');
                    break;
                case 1:
                    $query->orderBy('name', 'asc');
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
