<nav>
    <h1>{{ config('app.name', 'Customer') }}</h1>
    <ul>
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar Sesión</button>
            </form>
        @endauth
    </ul>
</nav>