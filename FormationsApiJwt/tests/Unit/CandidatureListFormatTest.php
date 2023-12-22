<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Formation;
use App\Models\Candidature;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class, Hash::class);

class CandidatureListTest extends TestCase
{
    public function testCandidatureListFormat()
    {
        // Créer un utilisateur avec le rôle "admin"
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Se connecter en tant qu'admin
        $loginResponse = $this->postJson('api/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $loginResponse->assertStatus(200);

        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];

        // Créer quelques candidatures pour le test
        $candidatures = collect();

        for ($i = 0; $i < 3; $i++) {
            $candidature = Candidature::create([
                'user_id' => User::create([
                    'name' => "Candidat User $i",
                    'email' => "candidate$i@example.com",
                    'password' => Hash::make('password'),
                    'role' => 'candidat',
                ])->id,
                'formation_id' => Formation::create([
                    'nom' => "Nom de la Formation $i",
                    'duree' => '6 mois',
                    'description' => "Description de la Formation $i",
                ])->id,
                'statut' => 'en attente',
            ]);

            $candidatures->push($candidature);
        }
        $response = $this->actingAs($admin)
        ->getJson('/api/candidate/list', [
        'Authorization' => 'Bearer ' . $token,
    ]);
        // Appeler le endpoint pour récupérer la liste des candidatures
        $response = $this->actingAs($admin)
            ->getJson('/api/candidate/list', [
                'Authorization' => 'Bearer ' . $token,
            ]);

        // Vérifier que la réponse a un format correct (par exemple, status_code, candidatures, etc.)
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status_code',
                'status_message',
                'candidature'
            ]);

        // Vérifier que le nombre de candidatures dans la réponse correspond au nombre créé
        // $response->assertJsonCount($candidatures->count(), 'candidatures');
    }
}
