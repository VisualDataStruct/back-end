<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RestoreAlgorithmTest extends TestCase
{
    public function testRestoreAlgorithmSuccess()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::onlyTrashed()->first();
        if ($algorithm === null) {
            $algorithm = \App\Models\Algorithm::first();
            $algorithm->delete();
        }
        $this->actingAs($user)->call('POST',
            '/classification/' . $algorithm->classification_id . '/algorithm/' . $algorithm->id);
        $this->assertResponseOk();
        $this->seeInDatabase('algorithm', [
            'id' => $algorithm->id,
            'deleted_at' => null,
        ]);
    }
    public function testRestoreAlgorithmFailWithNoLogin()
    {
        $algorithm = \App\Models\Algorithm::onlyTrashed()->first();
        if ($algorithm === null) {
            $algorithm = \App\Models\Algorithm::first();
            $algorithm->delete();
        }
        $response = $this->call('POST',
            '/classification/' . $algorithm->classification_id . '/algorithm/' . $algorithm->id);
        $this->assertEquals(401, $response->status());
    }
    public function testRestoreAlgorithmFailWithClassificationNotFound()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::onlyTrashed()->first();
        if ($algorithm === null) {
            $algorithm = \App\Models\Algorithm::first();
            $algorithm->delete();
        }
        $response = $this->actingAs($user)->call('POST',
            '/classification/0/algorithm/' . $algorithm->id);
        $this->assertEquals(404, $response->status());
    }
    public function testRestoreAlgorithmFailWithAlgorithmNotFound()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::onlyTrashed()->first();
        if ($algorithm === null) {
            $algorithm = \App\Models\Algorithm::first();
            $algorithm->delete();
        }
        $response = $this->actingAs($user)->call('POST',
            '/classification/' . $algorithm->classification_id . '/algorithm/0');
        $this->assertEquals(404, $response->status());
    }
}
