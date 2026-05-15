@extends('layouts.app')

@section('title', 'Редактирование мастер-класса')

@section('content')
<div class="row">
    <div class="row--small">
        <div class="edit-form">
            <form method="POST" action="{{ route('instructor.update', $masterClass) }}">
                @csrf
                <h2>Редактирование мастер-класса</h2>
                @method('PUT')
                
                <div>  
                    <div style="display: grid; grid-template-columns: 150px 1fr; gap: 10px;">
                        <div><strong>Вид творчества:</strong></div>
                        <div>{{ $masterClass->craftType->name }}</div>
                        
                        <div><strong>Название:</strong></div>
                        <div>{{ $masterClass->title }}</div>
                        
                        <div><strong>Дата:</strong></div>
                        <div>{{ $masterClass->dateFullFormatted }}</div>
                        
                        <div><strong>Время:</strong></div>
                        <div>{{ $masterClass->time_formatted }}</div>
                        
                        <div><strong>Количество мест:</strong></div>
                        <div>{{ $masterClass->max_participants }}</div>
                    </div>
                </div>
                
                <div style="margin-bottom: 25px;">
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="description">Описание мастер-класса</label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="6" 
                            class="form-control @error('description') is-invalid @enderror"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; resize: vertical;"
                            required>{{ old('description', $masterClass->description) }}</textarea>
                        @error('description')
                            <div class="error" style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 25px;">
                        <label for="price">Стоимость мастер-класса</label>
                        <input 
                            type="number" 
                            name="price" 
                            id="price" 
                            value="{{ old('price', $masterClass->price) }}"
                            min="0"
                            max="100000"
                            class="form-control @error('price') is-invalid @enderror"
                            style="width: 100%; padding: 10px; border: 1px solid #ddd;"
                            required>
                        @error('price')
                            <div class="error" style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Кнопки -->
                <div style="display: flex; gap: 15px; justify-content: space-between; padding-top: 20px; border-top: 1px solid #eee;">
                    <a href="{{ route('instructor.dashboard') }}" class="btn" style="width: 200px; text-decoration: none;">
                        Отмена
                    </a>
                    
                    <button type="submit" class="btn" style="background: #20416c; color: #fff; width: 200px;">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection