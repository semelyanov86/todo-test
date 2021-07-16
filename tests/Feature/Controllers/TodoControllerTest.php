<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Todo;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_todos()
    {
        $todos = Todo::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('todos.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.todos.index')
            ->assertViewHas('todos');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_todo()
    {
        $response = $this->get(route('todos.create'));

        $response->assertOk()->assertViewIs('app.todos.create');
    }

    /**
     * @test
     */
    public function it_stores_the_todo()
    {
        $data = Todo::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('todos.store'), $data);

        $this->assertDatabaseHas('todos', $data);

        $todo = Todo::latest('id')->first();

        $response->assertRedirect(route('todos.edit', $todo));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->get(route('todos.show', $todo));

        $response
            ->assertOk()
            ->assertViewIs('app.todos.show')
            ->assertViewHas('todo');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->get(route('todos.edit', $todo));

        $response
            ->assertOk()
            ->assertViewIs('app.todos.edit')
            ->assertViewHas('todo');
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

        $response = $this->put(route('todos.update', $todo), $data);

        $data['id'] = $todo->id;

        $this->assertDatabaseHas('todos', $data);

        $response->assertRedirect(route('todos.edit', $todo));
    }

    /**
     * @test
     */
    public function it_deletes_the_todo()
    {
        $todo = Todo::factory()->create();

        $response = $this->delete(route('todos.destroy', $todo));

        $response->assertRedirect(route('todos.index'));

        $this->assertDeleted($todo);
    }
}
