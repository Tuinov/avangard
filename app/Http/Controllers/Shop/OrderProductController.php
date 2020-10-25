<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Order;
use App\OrderProduct;
use App\Product;
use Illuminate\Http\Request;
use DB;

class OrderProductController extends Controller
{
    public static function index()
    {

        dd(self::getOrderProductsInOrder(1));
    }


    public static function getOrderProduct($id)
    {
        $OrderProduct = (new OrderProduct)::find($id);
        return $OrderProduct;

    }

    /**
     * Получает коллекцию продуктов по id orderProducts
     */
    public static function getOrderProductsInOrder($orderId)
    {
        $data = (new OrderProduct())::where('order_id', $orderId)->get();

        $orderProducts = $data->map(function ($item) {

            $product = (new Product)::find($item->product_id);
            return $product;
        });

        return $orderProducts;
    }

}
