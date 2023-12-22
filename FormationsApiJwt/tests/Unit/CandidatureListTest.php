<?php
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

uses(RefreshDatabase::class, Hash::class);

class CandidaturesListTest extends TestCase
{
    public function testAdminCanViewCandidaturesList()
    {
        // Créer un utilisateur avec le rôle admin
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Assurez-vous que vous avez une colonne 'role' dans votre modèle User
        ]);

        // Login en tant qu'utilisateur admin
        $loginResponse = $this->postJson('api/login', [
            'email' => $adminUser->email,
            'password' => 'password', // Utilisez le mot de passe en clair
        ]);

        $loginResponse->assertStatus(200);

        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];

        // Utiliser le token pour accéder à la liste des candidats
        $candidatsListResponse = $this->actingAs($adminUser)
            ->getJson('api/candidate/list', [
                'Authorization' => 'Bearer ' . $token,
            ]);

        // Vérifier que la liste des candidats est retournée avec le statut 200
        $candidatsListResponse->assertStatus(200);

        // Vérifications supplémentaires si nécessaire
    }

    
}
