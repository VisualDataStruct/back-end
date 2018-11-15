<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DeleteAlgorithmTest extends TestCase
{
    public function testDeleteAlgorithmSuccess()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::first();
        $this->actingAs($user)->call('DELETE',
            '/classification/' . $algorithm->classification_id . '/algorithm/' . $algorithm->id);
        $this->assertResponseOk();
        $this->notSeeInDatabase('algorithm', [
            'id' => $algorithm->id,
            'deleted_at' => null,
        ]);
    }
    public function testDeleteAlgorithmFailWithNoLogin()
    {
        $algorithm = \App\Models\Algorithm::first();
        $response = $this->call('DELETE',
            '/classification/' . $algorithm->classification_id . '/algorithm/' . $algorithm->id);
        $this->assertEquals(401, $response->status());
    }
    public function testDeleteAlgorithmFailWithClassificationNotFound()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::first();
        $response = $this->actingAs($user)->call('DELETE',
            '/classification/0/algorithm/' . $algorithm->id);
        $this->assertEquals(404, $response->status());
    }
    public function testDeleteAlgorithmFailWithAlgorithmNotFound()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::first();
        $response = $this->actingAs($user)->call('DELETE',
            '/classification/' . $algorithm->classification_id . '/algorithm/0');
        $this->assertEquals(404, $response->status());
    }
}
