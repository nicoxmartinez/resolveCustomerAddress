<form method="POST" action="{{ route('register.store') }}">
    @csrf
    <label for="name">Nombre:</label><br/>
    <input type="text" name="name" id="name" value="{{ old('name') }}" required/><br/>
    <label for="email">Email:</label><br/>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required/><br/>
    <label for="password">Contraseña:</label><br/>
    <input type="password" name="password" id="password" required/><br/>
    <label for="password_confirmation">Confirmar Contraseña:</label><br/>
    <input type="password" name="password_confirmation" id="password_confirmation" required/><br/>
    <button type="submit">Registrarse</button>
</form>