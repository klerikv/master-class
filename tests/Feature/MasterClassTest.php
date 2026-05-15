<?php

namespace Tests\Feature;

use App\Models\CraftType;
use App\Models\MasterClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterClassTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_see_master_classes_list()
    {
        $craftType = CraftType::factory()->create();
        $masterClass = MasterClass::factory()->create([
            'craft_type_id' => $craftType->id,
        ]);
        
        $response = $this->get('/show/' . $craftType->id);
        
        $response->assertStatus(200);
        $response->assertSee($masterClass->title);
    }

    public function test_instructor_can_create_master_class()
    {
        $instructor = User::factory()->instructor()->create();
        $craftType = CraftType::factory()->create();
        
        $response = $this->actingAs($instructor)->post('/instructor/create', [
            'craft_type_id' => $craftType->id,
            'title' => 'Новый мастер-класс',
            'description' => 'Описание мастер-класса',
            'date' => now()->addDays(5)->format('Y-m-d'),
            'time_slot' => '9-11',
            'max_participants' => 10,
            'price' => 1000,
        ]);
        
        $response->assertRedirect('/instructor/dashboard');
        $this->assertDatabaseHas('master_classes', [
            'title' => 'Новый мастер-класс',
            'instructor_id' => $instructor->id,
        ]);
    }

    public function test_visitor_cannot_create_master_class()
    {
        $visitor = User::factory()->visitor()->create();
        $craftType = CraftType::factory()->create();
        
        $response = $this->actingAs($visitor)->post('/instructor/create', [
            'craft_type_id' => $craftType->id,
            'title' => 'Новый мастер-класс',
            'description' => 'Описание мастер-класса',
            'date' => now()->addDays(5)->format('Y-m-d'),
            'time_slot' => '9-11',
            'max_participants' => 10,
            'price' => 1000,
        ]);
        
        $response->assertStatus(403);
    }

    public function test_instructor_can_update_master_class()
    {
        $instructor = User::factory()->instructor()->create();
        $masterClass = MasterClass::factory()->create([
            'instructor_id' => $instructor->id,
        ]);
        
        $response = $this->actingAs($instructor)->put('/instructor/update/' . $masterClass->id, [
            'description' => 'Новое описание',
            'price' => 2000,
        ]);
        
        $response->assertRedirect('/instructor/dashboard');
        $this->assertDatabaseHas('master_classes', [
            'id' => $masterClass->id,
            'description' => 'Новое описание',
            'price' => 2000,
        ]);
    }

    public function test_instructor_cannot_update_someone_elses_master_class()
    {
        $instructor1 = User::factory()->instructor()->create();
        $instructor2 = User::factory()->instructor()->create();
        $masterClass = MasterClass::factory()->create([
            'instructor_id' => $instructor1->id,
        ]);
        
        $response = $this->actingAs($instructor2)->put('/instructor/update/' . $masterClass->id, [
            'description' => 'Новое описание',
            'price' => 2000,
        ]);
        
        $response->assertStatus(403);
    }
}