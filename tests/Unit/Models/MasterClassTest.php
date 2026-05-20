<?php

namespace Tests\Unit\Models;

use App\Models\MasterClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterClassTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_class_has_craft_type()
    {
        $masterClass = MasterClass::factory()->create();

        $this->assertNotNull($masterClass->craftType);
    }

    public function test_master_class_has_instructor()
    {
        $masterClass = MasterClass::factory()->create();

        $this->assertNotNull($masterClass->instructor);
        $this->assertTrue($masterClass->instructor->isInstructor());
    }

    public function test_is_available_when_seats_available()
    {
        $masterClass = MasterClass::factory()->create(['max_participants' => 5]);

        $this->assertTrue($masterClass->isAvailable());
    }

    public function test_is_not_available_when_no_seats()
    {
        $masterClass = MasterClass::factory()->create(['max_participants' => 0]);

        $this->assertFalse($masterClass->isAvailable());
    }

    public function test_is_past_returns_true_for_past_master_class()
    {
        $masterClass = MasterClass::factory()->past()->create();

        $this->assertTrue($masterClass->isPast());
    }

    public function test_is_past_returns_false_for_future_master_class()
    {
        $masterClass = MasterClass::factory()->create();

        $this->assertFalse($masterClass->isPast());
    }

    public function test_can_book_returns_true_for_future_available_class()
    {
        $masterClass = MasterClass::factory()->create(['max_participants' => 10]);

        $this->assertTrue($masterClass->canBook());
    }

    public function test_can_book_returns_false_for_past_class()
    {
        $masterClass = MasterClass::factory()->past()->create();

        $this->assertFalse($masterClass->canBook());
    }

    public function test_can_book_returns_false_for_full_class()
    {
        $masterClass = MasterClass::factory()->full()->create();

        $this->assertFalse($masterClass->canBook());
    }
}
