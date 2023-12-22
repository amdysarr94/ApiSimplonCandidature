<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Formation;
use App\Models\Candidature;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class, Hash::class);

class CandidatureStoreTest extends TestCase
{
    public function testCandidatureStoreSuccess()
    {
        // Créer une formation
        $formation = Formation::create([
            'id'=>1,
            'nom' => 'Nom de la Formation',
            'duree' => '6 mois',
            'description'=> 'description de la Formation',
        ]);
        // Créer un utilisateur avec le rôle "candidat"
        $user = User::create([
            'name' => 'Candidat User',
            'email' => 'candidate10@example.com',
            'password' => Hash::make('password'),
            'role' => 'candidat',
        ]);
         

        // Se connecter en tant qu'utilisateur
        $loginResponse = $this->postJson('api/login', [
            
            'email' => $user->email,
            'password' => 'password',
        ]);

        $loginResponse->assertStatus(200);

        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];

       
        // Envoyer une candidature avec succès
        $response = $this->actingAs($user)
            ->postJson('/api/candidate', [
                // 'formation_id'=> $formation->id,
                'nom_formation' => $formation->nom,
                // Ajoutez d'autres données nécessaires pour la candidature
            ], [
                'Authorization' => 'Bearer ' . $token,
            ]);

        // Vérifier que la candidature a été créée avec succès
        $response->assertStatus(200)
            ->assertJson([
                'status_code' => 200,
                'status_message' => 'Candidature envoyé avec succès',
            ]);

        // Vérifier que la candidature a été enregistrée dans la base de données
        $this->assertDatabaseHas('candidatures', [
            'user_id' => $user->id,
            'formation_id' => $formation->id,
            // Ajoutez d'autres vérifications si nécessaire
        ]);
    }

    // public function testCandidatureStoreDuplicate()
    // {
    //     // Créer un utilisateur avec le rôle "candidat"
    //     $user = User::create([
    //         'name' => 'Candidat User',
    //         'email' => 'candidat@example.com',
    //         'password' => Hash::make('password'),
    //         'role' => 'candidat',
    //     ]);

    //     // Se connecter en tant qu'utilisateur
    //     $loginResponse = $this->postJson('api/login', [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ]);

    //     $loginResponse->assertStatus(200);

    //     // Récupérer le token après la connexion
    //     $token = $loginResponse->json()['authorisation']['token'];

    //     // Créer une formation
    //     $formation = Formation::create([

    //         'nom' => 'Backend',
    //         'duree' => '6 mois',
    //         'description' => 'développement backend',
    //         // Ajoutez d'autres attributs de formation si nécessaire
    //     ]);

    //     // Créer une candidature existante pour l'utilisateur et la formation
    //     Candidature::create([
    //         'user_id' => $user->id,
    //         'formation_id' => $formation->id,
    //     ]);

    //     // Tenter d'envoyer une candidature en double
    //     $response = $this->actingAs($user)
    //         ->postJson('/api/candidate', [
    //             'nom_formation' => $formation->nom,
    //             // Ajoutez d'autres données nécessaires pour la candidature
    //         ], [
    //             'Authorization' => 'Bearer ' . $token,
    //         ]);
    //     //Vérifier qu'un candidat peut candidater : 
    //         $response->assertStatus(200)
    //         ->assertJson([
    //             'status_code' => 200,
    //             'status_message' => 'Candidature envoyé avec succès',
    //         ]);
    //     //Vérifier que la réponse indique que la candidature est en double
    //     $response->assertStatus(403)
    //         ->assertJson([
    //             'status_code' => 403,
    //             'status_message' => 'Vous avez déjà candidaté à cette formation',
    //         ]);

    // }
}
