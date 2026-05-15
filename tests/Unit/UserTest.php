<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\MasterClass;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_visitor()
    {
        $user = User::factory()->visitor()->create();
        
        $this->assertTrue($user->isVisitor());
        $this->assertFalse($user->isInstructor());
    }

    public function test_user_can_be_instructor()
    {
        $user = User::factory()->instructor()->create();
        
        $this->assertTrue($user->isInstructor());
        $this->assertFalse($user->isVisitor());
    }

    public function test_user_has_master_classes_as_instructor()
    {
        $user = User::factory()->instructor()->create();
        $masterClass = MasterClass::factory()->create(['instructor_id' => $user->id]);
        
        $this->assertTrue($user->instructorMasterClasses->contains($masterClass));
    }
}