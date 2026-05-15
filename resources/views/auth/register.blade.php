@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row">
    <div class="row--small">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h2>Форма регистрации</h2>
            
            <div class="form-group">
                <label>ФИО</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" required>
                @error('full_name')
                    <div class="error" style="color: red;">{{ $message }}</div>
                @enderror
            </div>
            
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
                <label>Подтверждение пароля</label>
                <input type="password" name="password_confirmation" required>
            </div>
            
            <div class="form-group">
                <label>Номер телефона</label>
                <input type="tel" 
                    name="phone" 
                    id="phone" 
                    value="{{ old('phone') }}" 
                    placeholder="+7XXXXXXXXXX"
                    required
                    maxlength="12">
                @error('phone')
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
    // Автоматическая подстановка +7 и форматирование
    const phoneInput = document.getElementById('phone');

    phoneInput.addEventListener('focus', function() {
        if (!this.value) {
            this.value = '+7';
        }
    });
    
    phoneInput.addEventListener('input', function(e) {
        let value = this.value;
        
        if (value === '') {
            this.value = '+7';
            return;
        }

        if (!value.startsWith('+')) {
            value = '+7' + value.replace(/\D/g, '');
        }
        
        value = '+' + value.substring(1).replace(/\D/g, '');

        if (value.length > 12) {
            value = value.substring(0, 12);
        }
        
        this.value = value;
    });
</script>
@endpush