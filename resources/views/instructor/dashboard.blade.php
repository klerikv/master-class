@extends('layouts.app')

@section('title', 'Личный кабинет ведущего')

@section('body-class', 'dp')

@section('content')
<div class="row">
    <div class="hover"></div>
    <div class="title"></div>
    <div class="row--small grid between">
        <div class="content driver-page">
            <div class="driver-page-photo">
                @if($instructor->photo)
                    <img src="{{ asset('img/' . $instructor->photo) }}" alt="Фото ведущего">
                @else
                    <img src="{{ asset('img/driver-page.png') }}" alt="Фото ведущего">
                @endif
            </div>    
            <div class="driver-page-name">{{ $instructor->full_name }}</div>
            <div class="driver-page-text">
                <div class="driver-page-my">Мои мастер-классы</div>
                <table class="driver-page-table">
                    <tbody>
                        @forelse($masterClasses as $masterClass)
                            <tr>
                                <td style="width: 130px;">{{ $masterClass->dateFullFormatted }}<p> {{ $masterClass->time_formatted }}</td>
                                <td>
                                    <b>«{{ $masterClass->title }}»</b>
                                    <div>
                                        <a href="{{ route('instructor.edit-form', $masterClass) }}" style="color: #34aa75; font-weight: bold;">
                                            Редактировать
                                        </a>
                                    </div>
                                    
                                    <p>Цена: {{ $masterClass->price }}</p>
                                    <p>Участников: {{ $masterClass->bookings->count() }}/{{ $masterClass->max_participants }}</p>
                                    
                                    @if($masterClass->bookings->count() > 0)
                                        @foreach($masterClass->bookings as $booking)
                                            <p>
                                                {{ $loop->iteration }}. {{ $booking->user->full_name }}<br>
                                                email: {{ $booking->user->email }}<br>
                                                tel: {{ $booking->user->phoneFormatted }}
                                            </p>
                                        @endforeach
                                    @else
                                        <p>Нет участников</p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">У вас пока нет мастер-классов</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="driver-page-btn-wrapper">
                <a href="{{ route('instructor.create-form') }}">
                    <div class="driver-page-btn btn">
                        Добавить мастер-класс
                    </div>
                </a>
            </div>
        </div>
        <ul class="menu">
            @if(isset($craftTypes) && $craftTypes->count() > 0)
                @foreach($craftTypes as $craftTypeItem)
                    <li><a href="{{ route('craft-type.show', $craftTypeItem) }}">{{ $craftTypeItem->name }}</a></li>
                @endforeach
            @elseif(isset($allCraftTypes) && $allCraftTypes->count() > 0)
                @foreach($allCraftTypes as $craftTypeItem)
                    <li><a href="{{ route('craft-type.show', $craftTypeItem) }}">{{ $craftTypeItem->name }}</a></li>
                @endforeach
            @endif
        </ul>
    </div>
</div>
@endsection