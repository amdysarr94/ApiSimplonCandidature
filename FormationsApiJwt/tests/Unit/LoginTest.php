<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class, Hash::class);

class LoginTest extends TestCase
{
    public function testUserCanRegisterAndLogin()
    {
        // Générer une adresse e-mail unique
        $uniqueEmail = 'test_' . uniqid() . '@example.com';

        // Register user
        $user = [
            'name' => 'musa',
            'email' => $uniqueEmail,
            'password' => 'password', // Utilisez le mot de passe en clair
        ];

        $registrationResponse = $this->postJson('api/register', [
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
        ]);

        $registrationResponse->assertStatus(200);

        // Login user
        $loginResponse = $this->postJson('api/login', [
            'email' => $user['email'],
            'password' => $user['password'], 
        ]);

        $loginResponse->assertStatus(200);

        // Vérifications supplémentaires si nécessaire
    }
}
