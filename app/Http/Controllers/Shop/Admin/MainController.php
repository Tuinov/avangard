<?php

namespace App\Http\Controllers\Shop\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shop\ProductController;
use App\Order;
use App\OrderProduct;
use App\Partner;
use App\Product;
use Illuminate\Http\Request;
use DB;

class MainController extends Controller
{

    public function index()
    {
       // $orders = (new Order)->all();

        //dd($orders);
        $orders = $this->getOrders();
        return view('orders.index', compact('orders'));

    }

    public function edit($id)
    {
        $order = $this->getOrder($id);
        return view('orders.edit', compact('order'));

    }

    public function update(Request $request, $id)
    {

        $order = (new Order)->find($id);
        //dd($request->all(), $order);

        if (empty($order)) {
            return back()
                ->withErrors(['msg' => "Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();

        $order->client_email = $data['email'];
        $order->status = $data['status'];
        $order->save();

        $dataDelete = DB::table('order_products')->where('order_id', '=', $order->id)->delete();
        $productsId = $data['products_id'];

        foreach ($productsId as $productId) {
            $product = Product::find($productId);
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $productId;
            $orderProduct->quantity = 1;
            $orderProduct->price = $product->price;
            $orderProduct->save();
        }


        $partner = (new Partner())->find($order->partner_id);
        $partner->name = $data['name_partners'];
        $result = $partner->save();

        if ($result) {
            return redirect()
                ->route('order.edit', $order->id)
                ->with(['success' => 'успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения!"])
                ->withInput();
        }

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
            ->selectRaw('orders.id, orders.status, partners.name AS name_partners, SUM(order_products.price)AS sum, order_products.order_id')
            ->groupBy('orders.id')
            ->limit(30)
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
