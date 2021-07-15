<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function getAll($request)
    {
        $query = Book::query();

        $category = $request->category_id;
        $author = $request->author_id;
        $rating = $request->rating;
        $perPage = $request->get('perPage', 20);
        $sortType = $request->get('sortType', 'onSale');

        $query = $query->when($author, function($query, $author){
            return $query->where('author_id', $author);
        })->when($category, function($query, $category){
            return $query->where('category_id', $category);
        })->when($rating, function($query, $rating){
            return $query->withCount([ 'reviews as rating_average' 
            => function($query) {
                    $query->select(DB::raw('AVG(rating_start) as rating_average'));
                }
            ])->where('rating_average', '>=', $rating);
        });
        
        if($sortType == 'onSale')
            $query = $query->join('discounts', 'books.id', 'discounts.book_id')
                            ->select('books.*')
                            ->orderByRaw('(books.book_price - discounts.discount_price) desc')
                            ->orderBy('book_price', 'asc');
        if($sortType == 'priceAsc')
            $query = $query->orderBy('book_price', 'asc');
        if($sortType == 'priceDesc')
            $query = $query->orderBy('book_price', 'desc');
        if($sortType == 'popular')
            $query = $query->withCount(['reviews as reviews_count'])->orderByDesc('reviews_count')->orderBy('book_price', 'asc');
        $query = $query->paginate(100);
        //$query = $query->paginate($perPage);
        return $query;
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
