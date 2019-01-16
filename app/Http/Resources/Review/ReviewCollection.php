<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\Resource;

class ReviewCollection extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'customer' => $this->customer,
            'text' => $this->review,
            'star' => $this->star,
            'href' => [
                'link' => route('reviews.show',[$this->product_id,$this->id])
            ]
        ];
    }
}
