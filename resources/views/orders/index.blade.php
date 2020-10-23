@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @include('orders.includes.result_messages')

                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
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

                                <tr  style="background-color: #ccc;">
                                    <td><a href="">{{ $order['id'] }}</a></td>
                                    <td>{{ $order['name_partners'] }}</td>
                                    <td>{{ $order['sum'] }}</td>
                                    <td>{{ $order['products'] }}</td>
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