<?php

namespace Tests\Feature\Api;

use App\Models\Tag;
use App\Models\User;
use App\Models\Todo;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagTodosTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_tag_todos()
    {
        $tag = Tag::factory()->create();
        $todo = Todo::factory()->create();

        $tag->todos()->attach($todo);

        $response = $this->getJson(route('api.tags.todos.index', $tag));

        $response->assertOk()->assertSee($todo->title);
    }

    /**
     * @test
     */
    public function it_can_attach_todos_to_tag()
    {
        $tag = Tag::factory()->create();
        $todo = Todo::factory()->create();

        $response = $this->postJson(
            route('api.tags.todos.store', [$tag, $todo])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $tag
                ->todos()
                ->where('todos.id', $todo->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_todos_from_tag()
    {
        $tag = Tag::factory()->create();
        $todo = Todo::factory()->create();

        $response = $this->deleteJson(
            route('api.tags.todos.store', [$tag, $todo])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $tag
                ->todos()
                ->where('todos.id', $todo->id)
                ->exists()
        );
    }
}
