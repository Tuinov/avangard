@extends('layouts.app')

@section('content')
    @include('orders.includes.nav_orders')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('orders.includes.result_messages')

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <h2>Заказы</h2>
                            <tr>

                                <th>ид_заказа</th>
                                <th>название_партнера</th>
                                <th>стоимость_заказа</th>
                                <th>наименование_состав_заказа</th>
                                <th>статус_заказа</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)

                                <tr>
                                    <td><a href="{{ route('order.edit', $order['id']) }}">{{ $order['id'] }}. редакт.</a></td>
                                    <td>{{ $order['name_partners'] }}</td>
                                    <td>{{ $order['sum'] }}</td>
                                    <td>
                                        @foreach($order['products'] as $product)
                                            {{ $product->name }}
                                        @endforeach
                                    </td>
                                    <td>{{ $order['status'] }}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{--        {{ $paginator->links() }}--}}

    </div>

@endsection