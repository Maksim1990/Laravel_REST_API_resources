<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name'=>$this->name,
            'description'=>$this->detail,
            'price'=>$this->price,
            'stock'=>!empty($this->stock)?$this->stock:"Out of stock",
            'discount'=>$this->discount,
            'rating'=>$this->reviews->count()>0 ?$this->reviews->sum('star')/round($this->reviews->count(),2):"No rating",
            'total_price'=>round((1-($this->discount/100))*$this->price,2),
            'href'=>[
                'reviews'=>route('reviews.index',$this->id)
            ]
        ];
    }
}