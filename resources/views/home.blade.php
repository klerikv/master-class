@extends('layouts.app')

@section('title', 'Главная')

@section('body-class', 'dp')

@section('content')
<div class="row">
    <div class="hover"></div>
    <div class="title"></div>
    <div class="row--small grid between">
        <div class="content driver-page">
 
            <div class="driver-page-name">О компании</div>
            <div>
                <p>«ОчУмелые ручки» — это клуб любителей творчества, где каждый может найти себе занятие. 
                Мы проводим мастер-классы по различным видам рукоделия.</p>
            
            </div>
            
            @auth
                @if(Auth::user()->isVisitor() && isset($userBookings) && $userBookings->count() > 0)
                    <div class="my-bookings" style="margin-top: 30px;">
                        <h3>Мои записи на мастер-классы</h3>
                        @foreach($userBookings as $booking)
                            <div class="booking-item" style="border: 1px solid #ddd; padding: 10px; margin: 10px 0;">
                                <strong>{{ $booking->masterClass->title }}</strong><br>
                                Вид творчества: {{ $booking->masterClass->craftType->name }}<br>
                                Дата: {{ $booking->masterClass->dateFullFormatted }}<br>
                                Время: {{ $booking->masterClass->time_formatted }}<br>
                                Ведущий: {{ $booking->masterClass->instructor->full_name }}
                            </div>
                        @endforeach
                    </div>
                @endif
            @endauth
        </div>
        
        <ul class="menu">
            @foreach($craftTypes as $craftType)
                <li><a href="{{ route('craft-type.show', $craftType) }}">{{ $craftType->name }}</a></li>
            @endforeach
        </ul>
    </div>
</div>
@endsection