<?php

namespace Tests\Feature\Api;

use App\Models\Tag;
use App\Models\User;
use App\Models\Todo;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoTagsTest extends TestCase
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
    public function it_gets_todo_tags()
    {
        $todo = Todo::factory()->create();
        $tag = Tag::factory()->create();

        $todo->tags()->attach($tag);

        $response = $this->getJson(route('api.todos.tags.index', $todo));

        $response->assertOk()->assertSee($tag->name);
    }

    /**
     * @test
     */
    public function it_can_attach_tags_to_todo()
    {
        $todo = Todo::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->postJson(
            route('api.todos.tags.store', [$todo, $tag])
        );

        $response->assertNoContent();

        $this->assertTrue(
            $todo
                ->tags()
                ->where('tags.id', $tag->id)
                ->exists()
        );
    }

    /**
     * @test
     */
    public function it_can_detach_tags_from_todo()
    {
        $todo = Todo::factory()->create();
        $tag = Tag::factory()->create();

        $response = $this->deleteJson(
            route('api.todos.tags.store', [$todo, $tag])
        );

        $response->assertNoContent();

        $this->assertFalse(
            $todo
                ->tags()
                ->where('tags.id', $tag->id)
                ->exists()
        );
    }
}
