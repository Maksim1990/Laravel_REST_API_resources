<?php

namespace App\Http\Controllers;

use App\Exceptions\ReviewNotBelongsToProduct;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\Review\ReviewCollection;
use App\Http\Resources\Review\ReviewResource;
use App\Model\Product;
use App\Model\Review;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except('index','show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {

        return ReviewCollection::collection(Review::where('product_id',$product->id)->paginate(20));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReviewRequest $request,Product $product)
    {
        $request['review'] = $request->text;
        $request['product_id'] = $product->id;
        unset($request['text']);
        $review = new Review($request->all());
        $product->reviews()->save($review);
        return response([
            'data' => new ReviewResource($review)
        ],Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product,Review $review)
    {
        return new ReviewResource($review);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Product $product, Review $review)
    {
        $this->ReviewProductCheck($product,$review);
        $request['review'] = $request->text;
        unset($request['text']);
        $review->update($request->all());
        return response([
            'data' => new ReviewResource($review)
        ],Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product,Review $review)
    {
        $this->ReviewProductCheck($product,$review);
        $review->delete();
        return response(['data'=>"Review with id ".$review->id." was successfully deleted"],Response::HTTP_OK);
    }

    public function ReviewProductCheck($product,$review)
    {
        if ($product->id !== $review->product_id) {
            throw new ReviewNotBelongsToProduct;
        }
    }
}
