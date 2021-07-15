<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Book;
use Validator;

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
        $book = Book::find($id);
        if ($book) {
            //$data = $book->category()->get();
            return $this->responseSuccess($data);
        } else {
            return $this->responseError($data, "Book not found", 404);
        }
    }
}