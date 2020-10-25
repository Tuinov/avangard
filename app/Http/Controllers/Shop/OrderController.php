<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Product;
use DB;

class OrderController extends Controller
{

    public function index()
    {
        $orders = $this->getOrders();
        return view('orders.index', compact('orders'));

    }

    public function edit($id)
    {
        $order = $this->getOrder($id);
        return view('orders.edit', compact('order'));

    }

    public function getOrder($id)
    {
        $data = DB::table('orders')
            ->leftjoin('partners', 'orders.partner_id', '=', 'partners.id')
            ->join('order_products', 'orders.id', '=', 'order_products.order_id')
            ->leftjoin('products', 'order_products.product_id', '=', 'products.id')
            ->selectRaw('orders.id, orders.status, partners.name AS name_partners, SUM(order_products.price)AS sum, orders.client_email')
            ->groupBy('orders.id')
            ->where('orders.id', $id)
            ->get();

        $orders = $data->map(function ($item) {
            $data = [];
            $data['id'] = $item->id;
            $data['status'] = $item->status;
            $data['name_partners'] = $item->name_partners;
            $data['sum'] = $item->sum;
            $data['client_email'] = $item->client_email;
            $data['products'] = $this->getProducts($item->id);

            return $data;
        });

        $ordersToArray = $orders->toArray()[0];
        return $ordersToArray;
    }

    public function getOrders()
    {
        $data = DB::table('orders')
            ->leftjoin('partners', 'orders.partner_id', '=', 'partners.id')
            ->join('order_products', 'orders.id', '=', 'order_products.order_id')
            ->leftjoin('products', 'order_products.product_id', '=', 'products.id')
            ->selectRaw('orders.id, orders.status, partners.name AS name_partners, SUM(order_products.price)AS sum')
            ->groupBy('orders.id')
            ->limit(10)
            ->get();

        $orders = $data->map(function ($item) {
            $data = [];
            $data['id'] = $item->id;
            $data['status'] = $item->status;
            $data['name_partners'] = $item->name_partners;
            $data['sum'] = $item->sum;
            $data['products'] = $this->getProducts($item->id);

            return $data;
        });

        $ordersToArray = $orders->values()->keyBy('id')->toArray();
        return $ordersToArray;
    }

    public function getProducts($id)
    {
        $data = DB::table('orders')
            ->join('order_products', 'orders.id', '=', 'order_products.order_id')
            ->leftjoin('products', 'order_products.product_id', '=', 'products.id')
            ->selectRaw('products.id, products.name')
            ->where('orders.id', $id)
            ->get();

        $products = $data->map(function ($item) {

            $product = new Product;

            $product->id = $item->id;
            $product->name = $item->name;

            return $product;
        });

        return $products;
    }

}
