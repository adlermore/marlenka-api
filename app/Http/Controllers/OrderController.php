<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProducts;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    public function storeOrder(Request $request)
    {
        if(!$request->has("data") OR !$request->data){
            return $this->sendError("Data is incorrect");
        }
        try {
            DB::beginTransaction();
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->transaction = "transaction";
            $order->payment_amount = 50;
            $order->payment_status = 1;
            $order->payment_method = "card";
            $order->save();
            foreach ($request->data as $productData){
                if($productData["count"] AND $productData["count"] > 0){
                    $product = Product::find($productData["product_id"]);
                    if(!$product){
                        DB::rollBack();
                        return $this->sendError("Data is incorrect");
                    }
                    $orderProduct = new OrderProducts();
                    $orderProduct->order_id = $order->id;
                    $orderProduct->product_id = $product->id;
                    $orderProduct->price = $product->price;
                    $orderProduct->count = $productData["count"];
                    $orderProduct->save();
                }


            }

            DB::commit();
            return $this->sendResponse([],"");


        }catch (\Exception $e){
            DB::rollBack();
            return $this->sendError("Data is incorrect");

        }

    }

    public function getOrders(Request $request)
    {
        $orders = Order::where("user_id",Auth::user()->id);
        $orders = $orders->with("orderProducts.product");
        $orders = $orders->get();
        $data = [
            "orders" => $orders
        ];
        return $this->sendResponse($data,"");

    }

}
