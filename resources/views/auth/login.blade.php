<form method="POST" action="{{ route('login.authenticate') }}">
    @csrf
    <label for="email">Email:</label><br/>
    <input type="email" name="email" id="email" value="{{ old('email') }}" required/><br/>
    <label for="password">Contraseña:</label><br/>
    <input type="password" name="password" id="password" required/><br/>
    <label><input type="checkbox" name="remember" id="remember"> Recordarme</label><br/>
    <button type="submit">Ingresar</button>
</form>
<div>
    <a href="{{ route('register') }}">¿No tienes una cuenta? Regístrate</a>
</div>
  