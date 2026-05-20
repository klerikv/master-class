<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'full_name' => 'Тестовый Пользователь',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+79031234567',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'full_name' => 'Тестовый Пользователь',
        ]);
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        $response = $this->post('/register', [
            'full_name' => 'Тест',
            'email' => 'invalid',
            'password' => '123',
            'password_confirmation' => '456',
            'phone' => '123',
        ]);

        $response->assertSessionHasErrors(['full_name', 'email', 'password', 'phone']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->visitor()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->visitor()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->visitor()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
