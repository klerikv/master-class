@extends('layouts.app')

@section('title', 'Добавление мастер-класса')

@section('content')
<div class="row">
    <div class="row--small">
        <form method="POST" action="{{ route('instructor.create') }}" id="createForm">
            @csrf
            <h2>Форма добавления мастер-класса</h2>
            
            <div class="form-group">
                <label>Вид творчества</label>
                <select name="craft_type_id" class="@error('craft_type_id') is-invalid @enderror" required>
                    <option value="">Выберите вид творчества</option>
                    @foreach($craftTypes as $craftType)
                        <option value="{{ $craftType->id }}" {{ old('craft_type_id') == $craftType->id ? 'selected' : '' }}>
                            {{ $craftType->name }}
                        </option>
                    @endforeach
                </select>
                @error('craft_type_id')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Название мастер-класса</label>
                <input type="text" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Описание мастер-класса</label>
                <textarea name="description" rows="5" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Дата</label>
                <input type="date" name="date" id="date" value="{{ old('date') }}" required>
                @error('date')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Время</label>
                <select name="time_slot" id="time_slot" required>
                    <option value="">Сначала выберите дату</option>
                    <option value="9-11" {{ old('time_slot') == '9-11' ? 'selected' : '' }}>09:00 - 11:00</option>
                    <option value="11-13" {{ old('time_slot') == '11-13' ? 'selected' : '' }}>11:00 - 13:00</option>
                    <option value="13-15" {{ old('time_slot') == '13-15' ? 'selected' : '' }}>13:00 - 15:00</option>
                    <option value="15-17" {{ old('time_slot') == '15-17' ? 'selected' : '' }}>15:00 - 17:00</option>
                </select>
                @error('time_slot')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
                <div id="warningMsg" style="color: #ffc107; font-size: 12px; margin-top: 5px; display: none;"></div>
            </div>
            
            <div class="form-group">
                <label>Количество человек в группе</label>
                <input type="number" name="max_participants" min="1" max="100" value="{{ old('max_participants') }}" required>
                @error('max_participants')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Стоимость мастер-класса (₽)</label>
                <input type="number" name="price" min="0" value="{{ old('price') }}" required>
                @error('price')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Отправить</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('date');
        const timeSlotSelect = document.getElementById('time_slot');
        
        function checkSlots(date) {
            fetch(`/instructor/check-slots?date=${date}`)
                .then(response => response.json())
                .then(data => {
                    for(let i = 0; i < timeSlotSelect.options.length; i++) {
                        const option = timeSlotSelect.options[i];
                        const slotValue = option.value;
                        
                        if (slotValue === '') continue;
                        
                        if (data.occupied_slots.includes(slotValue)) {
                            option.disabled = true;
                        } else {
                            option.disabled = false;
                        }
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        }
        
        dateInput.addEventListener('change', function() {
            if (this.value) checkSlots(this.value);
        });
        
        if (dateInput.value) checkSlots(dateInput.value);
    });
</script>
@endpush

