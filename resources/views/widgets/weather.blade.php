@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h2>Текущая температура в Брянске: {{ $temperature }}</h2>
            </div>
            <div class="col-md-4">
                <a class="btn btn-warning" href="{{ route('weather') }}" role="button">Обновить</a>
            </div>
        </div>
    </div>

@endsection
