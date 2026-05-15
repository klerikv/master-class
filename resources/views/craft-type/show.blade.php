@extends('layouts.app')

@section('title', $craftType->name)

@section('content')
<div class="row">
    <div class="hover"></div>
    <div class="title">{{ $craftType->name }}</div>
    <div class="row--small grid between">
        <div class="content">
            @if($craftType->photo)
                <img src="{{ asset('img/' . $craftType->photo) }}" alt="{{ $craftType->name }}" style="width: 200px; margin-right: 15px;">
            @else
                <img src="{{ asset('img/elifant.png') }}" alt="{{ $craftType->name }}">
            @endif
            <p>{!! $craftType->description !!}</p>
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

    <div class="row shedule">
        <div class="row--small">
            <h2>Расписание</h2>
            <div class="drivers">
                @forelse($masterClasses as $masterClass)
                    <div class="driver grid" style="justify-content: space-between;">
                        <div class="driver-left grid">
                            <div class="driver-photo">
                                @if($masterClass->instructor->photo)
                                    <img src="{{ asset('img/' . $masterClass->instructor->photo) }}" alt="Фото">
                                @else
                                    <img src="{{ asset('img/driver1.png') }}" alt="Ведущий">
                                @endif
                            </div>
                            <div class="driver-text">
                                <div class="driver-name">{{ $masterClass->instructor->full_name }}</div>
                                <div class="driver-desc"><strong>«{{ $masterClass->title }}»</strong></div>
                                <div class="driver-desc small" style="word-break: break-word; overflow-wrap: break-word; white-space: normal;">
                                    {{ $masterClass->description }}
                                </div>
                            </div>
                        </div>
                        <div class="driver-right" style="width: 150px;">
                            @auth
                                @if(Auth::user()->isVisitor())
                                    @php
                                        $isBooked = Auth::user()->bookings()
                                            ->where('master_class_id', $masterClass->id)
                                            ->exists();
                                    @endphp
                                    
                                     @if($masterClass->isPast())
                                        <button class="driver-btn" disabled style="background: #6c757d;">
                                            Занятие прошло
                                        </button>
                                    @elseif($isBooked)
                                        <button class="driver-btn" disabled style="background: #6c757d;">Вы уже записаны</button>
                                    @elseif($masterClass->canBook())
                                        <a href="{{ route('booking.confirm-form', $masterClass) }}">
                                            <button class="driver-btn" type="button">записаться</button>
                                        </a>
                                    @else
                                        <button class="driver-btn" disabled style="background: #6c757d;">Нет мест</button>
                                    @endif
                                @endif
                            @else
                                <div style="font-size: 12px; color: #fff; margin-top: 10px; width: 120px;">
                                    Для записи необходимо <a href="{{ route('login') }}" style="color: #fff;">войти</a>
                                </div>
                            @endauth

                            <div class="driver-time" style="width: 120px;">{{ $masterClass->dateFullFormatted }} <p>{{ $masterClass->time_formatted }}</div>
                        </div>    
                    </div>
                @empty
                    <p>Нет доступных мастер-классов.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection