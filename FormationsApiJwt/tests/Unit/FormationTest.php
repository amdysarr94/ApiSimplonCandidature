<?php
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class, Hash::class);

class FormationListTest extends TestCase
{
    public function testUserCanViewFormationList()
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

        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];

        // Utiliser le token pour accéder à la liste des formations
        $formationListResponse = $this->getJson('api/formation/list', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        // Vérifier que la liste des formations est retournée avec le statut 200
        $formationListResponse->assertStatus(200);

        // Vérifications supplémentaires si nécessaire
    }
}
