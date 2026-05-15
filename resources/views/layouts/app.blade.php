<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Клуб любителей творчества «ОчУмелые ручки» - @yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/responsive.css') }}">
    @stack('styles')
</head>
<body @yield('body-class')>
    <div class="header">
        <div class="row grid middle between">
            <div class="logo">
                <a href="{{ route('home')  }}"> <img src="{{ asset('img/logo.png') }}" alt="Логотип"></a>
            </div>
            <div class="title">
                Клуб любителей творчества «ОчУмелые ручки»
            </div>
            <div class="auth" style="width: 100px;">
                @auth
                    @if(Auth::user()->isInstructor())
                        <a href="{{ route('instructor.dashboard') }}">Кабинет</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" style="display: inline; margin-left: 15px; padding: 0px;">
                        @csrf
                        <button type="submit" style="background: none; border: none; cursor: pointer; font-weight: bold; font-size: 16px;">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Вход</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="row row--nogutter">
        <div class="line"></div>
    </div>
    
    <div class="row row--nogutter">
        <div class="menu-burger">
            <div class="burger">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>    
    </div>    
    
    <div class="main">
        @if(session('success'))
            <div class="alert alert-success row" style="background: #d4edda; color: #155724; padding: 10px; margin: 0 auto;">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger row" style="background: #f8d7da; color: #721c24; padding: 10px; margin: 0 auto;">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info row" style="background: #d1ecf1; color: #0c5460; padding: 10px; margin: 0 auto;">
                {{ session('info') }}
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <div class="row row--nogutter">
        <div class="line"></div>
    </div>
    
    <div class="footer">
        <div class="row">
            <div class="row--small grid between">
                <div class="address">Наш адрес: ВДНХ, 120в</div>
                <div class="tel">Тел: 89123456765</div>
                <div class="copy">(с) Copyright, 2017</div>
            </div>
        </div>
    </div>
    
    @stack('scripts')
</body>
</html>