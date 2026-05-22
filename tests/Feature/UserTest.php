<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_professional_email()
    {
        $user = new User([
            'email' => 'john@entreprise.com'
        ]);

        $this->assertTrue($user->usesProfessionalEmail());
    }

    public function test_gmail_email()
    {
        $user = new User([
            'email' => 'john@gmail.com'
        ]);

        $this->assertFalse($user->usesProfessionalEmail());
    }
}