<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function scopeGetAll($category = null, $author = null)
    {
        $query = Book::all();
        
        $query->when($category, function($query, $category) {
            return $query->where('category_id', $category);
        })->when($author, function($query, $author) {
            return $query->where('author_id', $author);
        });
        dd($query);
        //$query->paginate($request->page);

        return $query->get();
    }
}
