<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Formation;
use App\Models\Candidature;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class, Hash::class);

class CandidatureAcceptTest extends TestCase
{
    public function testCandidatureAcceptSuccess()
    {
        // Créer un utilisateur avec le rôle "admin"
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Créer une formation
        $formation = Formation::create([
            'nom' => 'Nom de la Formation',
            'duree' => '6 mois',
            'description' => 'description de la Formation',
        ]);

        // Créer un utilisateur de type "candidat"
        $candidat = User::create([
            'name' => 'Candidat User',
            'email' => 'candidate@example.com',
            'password' => Hash::make('password'),
            'role' => 'candidat',
        ]);

        // Créer une candidature associée à la formation et au candidat
        $candidature = Candidature::create([
            'user_id' => $candidat->id,
            'formation_id' => $formation->id,
        ]);

        // Se connecter en tant qu'admin
        $loginResponse = $this->postJson('api/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $loginResponse->assertStatus(200);

        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];

        // Appeler le endpoint pour accepter la candidature
        $response = $this->actingAs($admin)
            ->putJson("/api/candidate/accept/{$candidature->id}", [], [
                'Authorization' => 'Bearer ' . $token,
            ]);

        // Vérifier que la candidature a été acceptée avec succès
        $response->assertStatus(200)
            ->assertJson([
                'status_code' => 200,
                'status_message' => 'Candidature accepté',
            ]);

        // Vérifier que le statut de la candidature a été mis à jour dans la base de données
        $this->assertDatabaseHas('candidatures', [
            'id' => $candidature->id,
            'statut' => 'accepté',
            // Ajoutez d'autres vérifications si nécessaire
        ]);
    }
}
