<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_book_master_class()
    {
        $user = User::factory()->visitor()->create();
        $masterClass = MasterClass::factory()->create(['max_participants' => 5]);

        $response = $this->actingAs($user)->post('/booking/confirm/'.$masterClass->id, [
            'action' => 'confirm',
        ]);

        $response->assertRedirect('/show/'.$masterClass->craft_type_id);
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'master_class_id' => $masterClass->id,
        ]);
    }

    public function test_user_cannot_book_same_master_class_twice()
    {
        $user = User::factory()->visitor()->create();
        $masterClass = MasterClass::factory()->create(['max_participants' => 5]);

        Booking::create([
            'user_id' => $user->id,
            'master_class_id' => $masterClass->id,
        ]);

        $response = $this->actingAs($user)->post('/booking/confirm/'.$masterClass->id, [
            'action' => 'confirm',
        ]);

        $response->assertRedirect('/show/'.$masterClass->craft_type_id);
        $response->assertSessionHas('error');

        $count = Booking::where('user_id', $user->id)
            ->where('master_class_id', $masterClass->id)
            ->count();

        $this->assertEquals(1, $count);
    }

    public function test_user_cannot_book_full_master_class()
    {
        $user = User::factory()->visitor()->create();
        $masterClass = MasterClass::factory()->full()->create(['max_participants' => 1]);

        $otherUser = User::factory()->visitor()->create();
        Booking::create([
            'user_id' => $otherUser->id,
            'master_class_id' => $masterClass->id,
        ]);

        $response = $this->actingAs($user)->post('/booking/confirm/'.$masterClass->id, [
            'action' => 'confirm',
        ]);

        $response->assertRedirect('/show/'.$masterClass->craft_type_id);
        $response->assertSessionHas('error');
    }

    public function test_user_cannot_book_past_master_class()
    {
        $user = User::factory()->visitor()->create();
        $masterClass = MasterClass::factory()->past()->create();

        $response = $this->actingAs($user)->get('/booking/confirm/'.$masterClass->id);

        $response->assertRedirect('/show/'.$masterClass->craft_type_id);
        $response->assertSessionHas('error');
    }

    public function test_instructor_cannot_book_master_class()
    {
        $instructor = User::factory()->instructor()->create();
        $masterClass = MasterClass::factory()->create();

        $response = $this->actingAs($instructor)->post('/booking/confirm/'.$masterClass->id, [
            'action' => 'confirm',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }
}
