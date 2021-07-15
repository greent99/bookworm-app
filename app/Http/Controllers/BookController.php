<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Review;
use App\Http\Requests\AddReviewRequest;
use Carbon\Carbon;

class BookController extends BaseController
{
    function __construct()
    {
        $book = new Book();
        $this->model = $book;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->model->getAll($request);
        return $this->responseSuccess($data);
    }

    public function detail($id)
    {
        $data = Book::with('category')->where('id', $id)->get();
        if ($data) {
            return $this->responseSuccess($data);
        } else {
            return $this->responseError($data, "Book not found", 404);
        }
    }

    public function getReview(Request $request, $id)
    {
        $sortType = $request->get('sortType', 'desc');
        $perPage = $request->get('perPage', 5);
        $data = Book::find($id)->reviews()->orderBy('review_date', $sortType)->paginate($perPage);
        if ($data) {
            return $this->responseSuccess($data);
        } else {
            return $this->responseError($data, "Book not found", 404);
        }
    }

    public function addReview(AddReviewRequest $request, $id)
    {
        $title = $request->title;
        $description = $request->description;
        $star = $request->star;

        $data = Review::create([
            'book_id' => $id,
            'review_title' => $title,
            'review_details' => $description,
            'rating_start' => $star,
            'review_date' => Carbon::now()
        ]);

        return $this->responseSuccess($data, "Success",201);
    }
}