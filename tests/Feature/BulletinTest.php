<?php

namespace Tests\Feature;

use App\Models\Bulletin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulletinTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/bulletins');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_bulletins_index(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/bulletins');
        $response->assertStatus(200);
        $response->assertSee('Nova Aliança');
    }

    public function test_public_bulletin_can_be_viewed_without_login(): void
    {
        $bulletin = Bulletin::create([
            'title' => 'BOLETIM',
            'sub_title' => 'PORTAL NOVA ALIANÇA',
            'tagline' => 'INFORMAÇÃO • CIDADANIA',
            'edition_date' => now(),
            'section_1_title' => 'CIDADE',
            'section_2_title' => 'ESTADO',
        ]);

        $response = $this->get('/folder/' . $bulletin->slug);
        $response->assertStatus(200);
        $response->assertSee('PORTAL DE NOTÍCIAS NOVA ALIANÇA');
    }
}
