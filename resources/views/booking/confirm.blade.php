@extends('layouts.app')

@section('title', 'Подтверждение записи')

@section('content')
<div class="row">
    <div class="row--small" style="max-width: 800px; margin: 0 auto;">
        <div class="booking-confirm">
            
            <div style="padding-top: 30px;">
                <h2>Подтверждение записи на мастер-класс</h2>
            </div>
                
                <!-- Информация о мастер-классе -->
                <div class="info-section" style="padding: 15px; margin-bottom: 20px; margin-top: 20px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px 0; width: 200px;"><strong>Вид творчества:</strong></td>
                            <td style="padding: 8px 0;">{{ $masterClass->craftType->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;"><strong>Название:</strong></td>
                            <td style="padding: 8px 0;">{{ $masterClass->title }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;"><strong>Описание:</strong></td>
                            <td style="padding: 8px 0;">{{ $masterClass->description }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;"><strong>Ведущий:</strong></td>
                            <td style="padding: 8px 0;">{{ $masterClass->instructor->full_name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;"><strong>Дата:</strong></td>
                            <td style="padding: 8px 0;">
                                <span style="color: #20416c; font-weight: bold; font-size: 14px;">
                                    {{ \Carbon\Carbon::parse($masterClass->date)->format('d.m.Y') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;"><strong>Время:</strong></td>
                            <td style="padding: 8px 0;">
                                <span style="color: #28a745; font-weight: bold; font-size: 14px;">
                                    {{ $masterClass->time_formatted }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;"><strong>Стоимость:</strong></td>
                            <td style="padding: 8px 0;">
                                <strong>
                                    {{ number_format($masterClass->price, 0, ',', ' ') }} ₽
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0;"><strong>Свободных мест:</strong></td>
                            <td style="padding: 8px 0;">
                                {{ $masterClass->availableSeats() }}
                            </td>
                        </tr>
                    </table>
                </div>
                
                
                <!-- Кнопки действий -->
                <div style="display: flex; gap: 15px; justify-content: center; padding: 20px 0 10px;">
                    <form method="POST" action="{{ route('booking.confirm', $masterClass) }}" style="flex: 1;">
                        @csrf
                        <button type="submit" 
                                name="action" 
                                value="cancel" 
                                class="btn btn-danger"
                                style="width: 100%; padding: 14px; font-size: 16px;">
                            Отмена
                        </button>
                    </form>
                    
                
                    <form method="POST" action="{{ route('booking.confirm', $masterClass) }}" style="flex: 1;">
                        @csrf
                        <button type="submit" 
                                name="action" 
                                value="confirm" 
                                class="btn btn-success"
                                style="width: 100%; padding: 14px; background: #20416c; color: white; font-size: 16px;">
                            Подтвердить
                        </button>
                    </form>
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection