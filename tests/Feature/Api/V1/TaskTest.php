<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class TaskTest extends TestCase
{
    public function test_user_dapat_melihat_daftar_task()
    {
        // arranged = buat 2 task baru di database
        $tasks = Task::factory()->count(2)->create();

        // act 
        $response = $this->getJson('/api/v1/tasks');

        // assert (mengecek)
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        $response->assertJsonFragment([
            'id' => $stasks[0]->id,
            'name' => $stasks[0]->name,
            'is_completed' => $stasks[0]->is_completed,
        ])

        $respone->assertJasonStructure([
            '+' => [
                'id',
                'name',
                'is_completed',
            ]
        ])
    }

    public function test_user_dapat_melihat_detail_task()
    {
        // arranged = buat 1 task baru di database
        $task = Task::factory()->create();

        // act 
        $response = $this->getJson('/api/v1/tasks/' . $task->id);

        // assert (mengecek)
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $task->id,
            'name' => $task->name,
            'is_completed' => $task->is_completed,
        ]);
    }
}

// class TaskTest extends TestCase
// {
//     public function test_index_return_a_successful_response()
//     {
//         // Arange = buat 1 task baru di database
//         $tasks = Task::factory()->count(2)->create();

//         // Act
//         $response = $this->getJson('/api/v1/tasks');

//         // Assert
//         $response->assertStatus(200);
//     }
// }
