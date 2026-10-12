<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dapat_melihat_daftar_task(): void
    {
        // arranged = buat 2 task baru di database
        $task = Task::factory()->count(2)->create();
        // act 
        $response = $this->getJson('/api/v1/tasks');
        // assert (mengecek)
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        // $response->assertJsonFragment([
        //     'id' => $stasks[0]->id,
        //     'name' => $stasks[0]->name,
        //     'is_completed' => $stasks[0]->is_completed,
        // ])

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'is_compleated',
                ]
            ]
        ]);
    }

    public function test_user_dapat_melihat_satu_task ():void
    {
        // arrrange
        $task = Task::factory()->create();
        // act
        $response = $this->getJson('api/v1/tasks/' . $task->id); // titik untuk menggabungkan string
        // assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_compleated',
            ]
        ]);
    }

    // post = insertdata
    public function test_user_dapat_insert_task(): void
    {
        // arrange
        $response = $this->postJson('api/v1/tasks',
        [
            'name' => 'new task',
            'is_compleated' => 1,
        ]);
        // act
        // assert
        $response->assertCreated();
        $this->assertDatabaseHas('tasks', [
            'name' => 'new task',
            'is_compleated' => 1,
        ]);
    }

    // update
    public function test_user_dapat_update(): void
    {
        // arrange
        $task = Task::factory()->create();
        // act
        $response = $this->putJson('api/v1/tasks/' . $task->id, [
            'name' => 'saya update'
        ]);
        // assert
        $response->assertOk();    // pengganti assertStatus(200)
        $response->assertJsonFragment([
            'name' => 'saya update'
        ]);
    }

    // delete
    public function test_user_dapat_delete(): void
    {
        // arrange
        $task = Task::factory()->create();
        // act
        $response = $this->deleteJson('api/v1/tasks/' . $task->id);
        // assert
        // $response->assertOk(); 
        $response->assertNoContent(); 
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id
        ]);
    }
}
