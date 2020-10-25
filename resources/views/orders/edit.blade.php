@extends('layouts.app')

@section('content')
    @include('orders.includes.nav_orders')
    <div class="container">

{{--        @php dd($order) @endphp--}}

        <form method="POST" action="">

            <div class="row">
                <div class="col-md-8">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">

                                    <h2>Редактировать заказ:</h2>
                                </div>

                                <div class="card-body">
                                    <div class="card-title"></div>

                                    <div class="tab-content">
                                        <div class="tab-pane active" id="maindata" role="tabpanel">
                                            <div class="form-group">
                                                <label for="email">Емаил клиента:</label>
                                                <input name="email" value="{{ $order['client_email'] }}"
                                                       id="email"
                                                       type="text"
                                                       class="form-control"
                                                       minlength="3"
                                                       required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name_partners">Партнёр:</label>
                                                <input name="name_partners" value="{{ $order['name_partners'] }}"
                                                       id="name_partners"
                                                       type="text"
                                                       class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label for="status">Статус:</label>
                                                <input name="status" value="{{ $order['status'] }}"
                                                       id="status"
                                                       type="text"
                                                       class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="sum">Сумма:</label>
                                                <input name="sum" value="{{ $order['sum'] }}"
                                                       id="sum"
                                                       type="text"
                                                       class="form-control">
                                            </div>
                                            <div class="form-check">
                                                <h5>Продукты:</h5>
                                                @foreach($order['products'] as $product)
                                                    <input name="is_published" type="checkbox" checked="checked" class="form-check-input"
                                                           value="{{ $product->id }}">
                                                    <label class="form-check-label" for="is_published">{{ $product->name }}</label>
                                                    <br>

                                                @endforeach



                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>


    </div>
@endsection
