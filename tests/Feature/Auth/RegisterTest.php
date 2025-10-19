<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_registers_a_user_and_redirects_to_articles_index()
    {
        // Données d'entrée simulées
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Exécution de la requête POST sur la route de register
        $response = $this->post('/register', $data);

        // Vérifie que la redirection est correcte
        $response->assertRedirect(route('articles.index'));

        // Vérifie que l'utilisateur a bien été créé en BDD
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);

        // Vérifie que le mot de passe est bien haché
        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue(Hash::check('password', $user->password));

        // Vérifie que l'utilisateur est bien authentifié
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function it_requires_all_fields_for_registration()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }

    /** @test */
    public function it_requires_a_unique_email()
    {
        User::factory()->create(['email' => 'john@example.com']);

        $response = $this->post('/register', [
            'name' => 'Jane',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_requires_password_confirmation()
    {
        $response = $this->post('/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'different_password',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
