<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegisterRequestTest extends TestCase
{
    private function validate($data)
    {
        $request = new RegisterRequest();
        return Validator::make($data, $request->rules());
    }

    public function test_valid_data_passes()
    {
        $validator = $this->validate([
            'full_name' => 'Иванов Иван',
            'email' => 'ivan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+79031234567',
        ]);
        
        $this->assertTrue($validator->passes());
    }

    public function test_full_name_required()
    {
        $validator = $this->validate([
            'full_name' => '',
            'email' => 'ivan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+79031234567',
        ]);
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('full_name', $validator->errors()->toArray());
    }

    public function test_email_format_validation()
    {
        $validator = $this->validate([
            'full_name' => 'Иванов Иван',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+79031234567',
        ]);
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function test_password_min_length()
    {
        $validator = $this->validate([
            'full_name' => 'Иванов Иван',
            'email' => 'ivan@example.com',
            'password' => '123',
            'password_confirmation' => '123',
            'phone' => '+79031234567',
        ]);
        
        $this->assertTrue($validator->fails());
    }

    public function test_password_confirmation_must_match()
    {
        $validator = $this->validate([
            'full_name' => 'Иванов Иван',
            'email' => 'ivan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
            'phone' => '+79031234567',
        ]);
        
        $this->assertTrue($validator->fails());
    }

    public function test_phone_format_validation()
    {
        $validator = $this->validate([
            'full_name' => 'Иванов Иван',
            'email' => 'ivan@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+7(903)123-45-67',
        ]);
        
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('phone', $validator->errors()->toArray());
    }
}