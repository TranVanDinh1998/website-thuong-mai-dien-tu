<?php

namespace App\Models;

use App\Http\Helpers\UploadImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id',
        'name',
        'description',
        'image',
        'category_id',
        'priority',
        'verified',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // relationship
    public function tagProducts() {
        return $this->hasMany(tagProduct::class);
    }
    // scope
    public function scopeActive($q)
    {
        return $q->whereVerified(1);
    }
    public function scopeInactive($q)
    {
        return $q->whereVerified(0);
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

    protected static function boot() {
        parent::boot();
        self::deleting(function($tag) {
            $tag->tagProducts()->delete();
            if ($tag->forceDeleting) {
                $tag->tagProducts()->forceDelete();
            }
        });
        self::restoring(function($tag) {
            $tag->tagProducts()->onlyTrashed()->restore();
        });
    }

}
