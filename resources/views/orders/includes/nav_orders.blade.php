<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link" href="{{ route('orders') }}">Все заказы<span class="sr-only">(current)</span></a>
            <a class="nav-item nav-link" href="{{ route('orders.late') }}">Просроченные</a>
            <a class="nav-item nav-link" href="{{ route('orders.now') }}">Текущие</a>
            <a class="nav-item nav-link" href="{{ route('orders.new') }}">Новые</a>
            <a class="nav-item nav-link" href="{{ route('orders.completed') }}">Выполненные</a>
        </div>
    </div>
</nav>