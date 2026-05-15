@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row">
    <div class="row--small">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h2>Вход в систему</h2>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Пароль</label>
                <input type="password" name="password" required>
                @error('password')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Войти</button>
            </div>
        </form>

        <div style="width: 50%;margin: 0 auto; padding-bottom: 10px;">Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></div>
    </div>
</div>
@endsection