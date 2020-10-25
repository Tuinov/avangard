<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Order;
use App\OrderProduct;
use App\Product;
use Illuminate\Http\Request;
use DB;

class ProductController extends Controller
{
    public static function index()
    {
        dd(self::getProductsInOrderProduct(1));
    }

    public static function getProduct($id)
    {
        $product = (new Product)::find($id);
        return $product;

    }

    public static function getProductsInOrderProduct($orderId)
    {
        $OrderProducts = OrderProductController::getOrderProductsInOrder($orderId);

        return $OrderProducts;

    }

}
