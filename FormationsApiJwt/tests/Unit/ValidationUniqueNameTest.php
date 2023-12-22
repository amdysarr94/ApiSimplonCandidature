<?php
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CandidatValidationTest extends TestCase
{
    public function testCandidatValidationRules()
    {
        // Créer un utilisateur avec le rôle de candidat
        $candidatUser = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
            'role' => 'candidat', // Assurez-vous que vous avez une colonne 'role' dans votre modèle User
        ]);

        // Login en tant qu'utilisateur candidat
        $loginResponse = $this->postJson('api/login', [
            'email' => $candidatUser->email,
            'password' => 'password', // Utilisez le mot de passe en clair
        ]);

        $loginResponse->assertStatus(200);

        // Récupérer le token après la connexion
        $token = $loginResponse->json()['authorisation']['token'];

        // Tenter de créer un autre utilisateur candidat avec les mêmes informations (devrait échouer)
        $duplicateCandidatResponse = $this->actingAs($candidatUser)
            ->postJson('api/register', [
                'name' => 12344, // Nom en double
                'email' => 'johndoe@example.com', // Email en double
                'password' => 'password',
                'role' => 'candidat',
            ]);

        // Vérifier que la création est refusée avec le statut 422 et les erreurs attendues
        $duplicateCandidatResponse->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        // Vérifications supplémentaires si nécessaire
    }
}
