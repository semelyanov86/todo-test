<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Todo;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoTest extends TestCase
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
    public function it_gets_todos_list()
    {
        $todos = Todo::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.todos.index'));

        $response->assertOk()->assertSee($todos[0]->title);
    }

    /**
     * @test
     */
    public function it_stores_the_todo()
    {
        $data = Todo::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.todos.store'), $data);

        $this->assertDatabaseHas('todos', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_todo()
    {
        $todo = Todo::factory()->create();

        $data = [
            'title' => $this->faker->sentence(10),
            'content' => $this->faker->text,
            'due_date' => $this->faker->dateTime,
            'is_done' => $this->faker->boolean,
        ];

        $response = $this->putJson(route('api.todos.update', $todo), $data);

        $data['id'] = $todo->id;

        $this->assertDatabaseHas('todos', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->deleteJson(route('api.todos.destroy', $todo));

        $this->assertDeleted($todo);

        $response->assertNoContent();
    }
}
