<?php

namespace App\Http\Controllers\Shop\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Requests\OrderUpdateRequest;
use App\Order;
use App\OrderProduct;
use App\Partner;
use App\Product;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Mail;

class MainController extends Controller
{

    protected $repository;

    public function __construct()
    {
        $this->repository = OrderRepository::getInstance();
    }

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

    public function update(OrderUpdateRequest $request, $id)
    {

        $order = (new Order)->find($id);

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

        if ($order->status == 20) {
            $userMail = $data['email'];

            Mail::send('emails.orderEnd', $order, function ($message) use ($userMail) {
                $message->to($userMail)->subject('заказ завершён ');
//                $message->from($user_email, '');
            });
        }

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

    public function getOrdersLate()
    {
        $between = [0, Carbon::now()];
        $orders = $this->repository->getDeliverySort($between, 'desc', 10);
        return view('orders.index', compact('orders'));
    }

    public function getOrdersNow()
    {
        $between = [Carbon::now(), Carbon::now()->addHours(24)];

        $orders = $this->repository->getDeliverySort($between, 'asc', 10);
        return view('orders.index', compact('orders'));
    }

    public function getOrdersNew()
    {
        $between = [Carbon::now(), Carbon::now()->addYears(0)];

        $orders = $this->repository->getDeliverySort($between, 'asc', 10);
        return view('orders.index', compact('orders'));
    }

    public function getOrdersСompleted()
    {
        $date = Carbon::today();
        $between = [$date, $date];

        $orders = $this->repository->getDeliverySort($between, 'desc', 20);
        return view('orders.index', compact('orders'));
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
