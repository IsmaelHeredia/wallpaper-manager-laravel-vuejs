<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wallpaper;
use App\Models\Estacion;
use App\Models\Horario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\ClientRepository;

class ApiEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;


    protected function setUp(): void
    {
        parent::setUp();

        $estaciones = Estacion::factory()->count(4)->create();
        $horarios = Horario::factory()->count(4)->create();

        Wallpaper::factory()->count(10)->create()->each(function ($wallpaper) use ($estaciones, $horarios) {
            $wallpaper->estaciones()->attach($estaciones->random(rand(1, 2))->pluck('id')->toArray());
            $wallpaper->horarios()->attach($horarios->random(rand(1, 2))->pluck('id')->toArray());
        });

        $clientRepository = new ClientRepository();
        $clientRepository->createPasswordGrantClient(
            null,
            'Testing Password Client',
            'http://localhost'
        );

    }

    public function test_el_usuario_puede_iniciar_sesion()
    {
        $client = DB::table('oauth_clients')->where('password_client', true)->first();

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => 'test@example.com',
            'password' => 'secret123',
            'scope' => '',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    }


    public function test_el_usuario_autenticado_puede_validarse()
    {
        $user = $this->authUser();

        $response = $this->getJson('/api/v1/validar');

        $response->assertStatus(200)->assertJsonFragment(['email' => $user->email]);
    }

    public function test_el_usuario_puede_actualizar_su_perfil()
    {
        $this->authUser();

        $response = $this->putJson('/api/v1/cuenta/actualizar', [
            'email' => 'nuevo@email.com',
            'currentPassword' => 'password',
        ]);

        $response->assertStatus(200)->assertJsonFragment(['email' => 'nuevo@email.com']);
    }

    public function test_se_pueden_listar_las_estaciones()
    {
        $this->authUser();

        $response = $this->getJson('/api/v1/estaciones');

        $response->assertStatus(200)->assertJsonStructure([['id', 'nombre']]);
    }

    public function test_se_puede_cargar_una_estacion()
    {
        $this->authUser();

        $estacion = Estacion::first();

        $response = $this->getJson("/api/v1/estaciones/{$estacion->id}");
        $response->assertStatus(200)->assertJsonFragment(['id' => $estacion->id]);
    }

    public function test_se_pueden_listar_los_horarios()
    {
        $this->authUser();

        $response = $this->getJson('/api/v1/horarios');
        $response->assertStatus(200);
    }

    public function test_se_puede_cargar_un_horario()
    {
        $this->authUser();

        $horario = Horario::first();

        $response = $this->getJson("/api/v1/horarios/{$horario->id}");
        $response->assertStatus(200)->assertJsonFragment(['id' => $horario->id]);
    }

    public function test_se_pueden_listar_los_wallpapers()
    {
        $this->authUser();

        Wallpaper::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/wallpapers');

        $response->assertOk()
            ->assertJsonStructure(['*' => ['id', 'nombre', 'calificacion', 'es_favorita']]);
    }

    public function test_se_puede_cargar_un_wallpaper()
    {
        $this->authUser();

        $wallpaper = Wallpaper::factory()->create();

        $response = $this->getJson("/api/v1/wallpapers/{$wallpaper->id}");

        $response->assertOk()
            ->assertJsonFragment(['id' => $wallpaper->id]);
    }

    public function test_se_puede_crear_un_wallpaper()
    {
        $this->authUser();

        Storage::fake('public');

        $estaciones = Estacion::factory()->count(2)->create();
        $horarios = Horario::factory()->count(2)->create();

        $data = [
            'nombre' => 'Wallpaper Test',
            'imagen' => UploadedFile::fake()->image('wall.jpg'),
            'calificacion' => 4,
            'es_favorita' => true,
            'estaciones_ids' => $estaciones->pluck('id')->toArray(),
            'horarios_ids' => $horarios->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/v1/wallpapers', $data);

        $response->assertCreated()
            ->assertJsonFragment(['nombre' => 'Wallpaper Test']);

        Storage::disk('public')->assertExists('wallpapers/' . basename($response['imagen']));
    }

    public function test_se_puede_actualizar_un_wallpaper()
    {
        $this->authUser();

        $wallpaper = Wallpaper::factory()->create([
            'nombre' => 'Viejo Nombre',
        ]);

        $estacion = Estacion::factory()->create();

        $data = [
            'nombre' => 'Nuevo Nombre',
            'calificacion' => 5,
            'es_favorita' => false,
            'estaciones_ids' => [$estacion->id],
        ];

        $response = $this->putJson("/api/v1/wallpapers/{$wallpaper->id}", $data);

        $response->assertOk()
            ->assertJsonFragment(['nombre' => 'Nuevo Nombre']);
    }

    public function test_se_puede_eliminar_un_wallpaper()
    {
        $this->authUser();

        $wallpaper = Wallpaper::factory()->create();

        $response = $this->deleteJson("/api/v1/wallpapers/{$wallpaper->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('wallpapers', ['id' => $wallpaper->id]);
    }

    public function test_las_estadisticas_devuelve_datos_correctos()
    {
        $this->authUser();

        $response = $this->getJson('/api/v1/estadisticas');
        $response->assertStatus(200)
            ->assertJsonStructure(['por_estacion', 'por_horario']);
    }
}
