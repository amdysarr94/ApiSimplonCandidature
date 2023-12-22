<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function PHPUnit\Framework\assertTrue;

uses(RefreshDatabase::class, Hash::class);

class AuthenticationTest extends TestCase
{
    public function testUserCanRegister()
    {
        $user = [
            'name' => 'musa',
            'email' => 'musa@gmail.com',
            'password' => Hash::make('password'),
        ];

        $response = $this->postJson('api/register', [
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
        ]);

        $response->assertStatus(200)->assertJsonStructure(['message']);
    }
}
