<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    public function getProducts(Request $request)
    {
        $products = new Product();

        if($request->has("category_id") AND $request->category_id){
            $products =  $products->where("category_id",$request->category_id);
        }
        if($request->has("product_id") AND $request->product_id){
            $products =  $products->where("id",$request->product_id);
        }
        if($request->has("filter") AND $request->filter){
            $products = $products
                ->where("name","LIKE","%{$request->filter}%")
                ->orWhere("description","LIKE","%{$request->filter}%")
                ->orWhere("details_one","LIKE","%{$request->filter}%")
                ->orWhere("details_two","LIKE","%{$request->filter}%")
                ->orWhere("details_three","LIKE","%{$request->filter}%")
            ;
        }
        if($request->has("offset") AND $request->offset){
            $products = $products->skip($request->offset);
        }
        if($request->has("limit") AND $request->limit){
            $products = $products->take($request->limit);
        }
        $products = $products->with("images","technicals","category")->get();
        $data = [
            "products" => $products,
        ];
        return $this->sendResponse($data,"");

    }

}
