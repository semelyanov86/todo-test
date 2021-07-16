<?php

namespace Tests\Feature\Api;

use App\Actions\StoreTagsAction;
use App\Actions\StoreToDoAction;
use App\Models\Tag;
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
            'due_date' => '2021-07-05',
            'is_done' => $this->faker->boolean,
        ];
        try {
            $response = $this->putJson(route('api.todos.update', $todo), $data);
        } catch (\Illuminate\Validation\ValidationException $exception) {
            $this->assertFalse(true, $exception->getMessage());
        }


        $data['id'] = $todo->id;

        $this->assertDatabaseHas('todos', $data);
        $data['due_date'] = '2021-07-05T00:00:00.000000Z';
        $response->assertStatus(202)->assertJsonFragment($data);
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

    /**
     * @test
     */
    public function it_can_manage_tags_during_creation()
    {
        Tag::create(['name' => 'first']);

        StoreTagsAction::run(['first', 'second']);

        $this->assertDatabaseCount('tags', 2);
        $this->assertDatabaseHas('tags', ['name' => 'second']);
    }

    /**
     * @test
     */
    public function it_can_create_todo_with_tags()
    {
        /** @var Todo $todo */
        $todo = StoreToDoAction::run([
            'title' => 'title field',
            'content' => 'content field',
            'due_date' => '2021-09-09',
            'is_done' => false,
            'tags' => ['one', 'two']
        ]);
        $this->assertDatabaseHas('todos', [
            'title' => 'title field',
            'content' => 'content field'
        ]);
        $this->assertDatabaseHas('tags', ['name' => 'one']);
        $this->assertDatabaseHas('tags', ['name' => 'two']);
        $this->assertEquals(2, $todo->tags->count());
    }
}
