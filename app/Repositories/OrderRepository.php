<?php


namespace App\Repositories;

use App\Http\Controllers\Shop\ProductController;
use App\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use DB;

class OrderRepository
{
    protected static $instance;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new OrderRepository();
        }

        return static::$instance;
    }

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}


    public function getDeliverySort($between = [], $sorting = 'ASC', $status)
    {

        $data = DB::table('orders')
            ->leftjoin('partners', 'orders.partner_id', '=', 'partners.id')
            ->join('order_products', 'orders.id', '=', 'order_products.order_id')
            ->leftjoin('products', 'order_products.product_id', '=', 'products.id')
            ->selectRaw('orders.id, orders.status, partners.name AS name_partners, SUM(order_products.price)AS sum, order_products.order_id')
            ->groupBy('orders.id')
            ->whereBetween('orders.delivery_dt', $between)
            ->where('orders.status', $status)
            ->orderBy('orders.delivery_dt',$sorting)
            ->limit(50)
            ->get();

        $orders = $data->map(function ($item) {
            $data = [];
            $data['id'] = $item->id;
            $data['status'] = $item->status;
            $data['name_partners'] = $item->name_partners;
            $data['sum'] = $item->sum;
            $data['products'] = ProductController::getProductsInOrderProduct($item->order_id);

            return $data;
        });

        $ordersToArray = $orders->values()->keyBy('id')->toArray();
        return $ordersToArray;
    }

}