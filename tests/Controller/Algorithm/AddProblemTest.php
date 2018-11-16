<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AddProblemTest extends TestCase
{
    public function testAddProblemSuccess()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $problem = [
            'name' => 'problem ' . rand(0, 1000000),
            'link' => 'http://problem.link.com/' . rand(0, 1000000),
        ];
        $this->actingAs($user)->call('POST',
            '/classification/' . $algorithm->classification_id . '/algorithm/' . $algorithm->id . '/problem', $problem);
        $this->assertResponseOk();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $pros = $algorithm->problems;
        foreach ($pros as $key => $pro) {
            $pros[$key] = json_encode($pro);
        }
        $this->assertContains(json_encode($problem), $pros);
    }
    public function testAddProblemFailWithNoLogin()
    {
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $problem = [
            'name' => 'problem ' . rand(0, 1000000),
            'link' => 'http://problem.link.com/' . rand(0, 1000000),
        ];
        $response = $this->call('POST',
            '/classification/' . $algorithm->classification_id . '/algorithm/' . $algorithm->id . '/problem', $problem);
        $this->assertEquals(401, $response->status());
    }
    public function testAddProblemFailWithClassificationNotFound()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $problem = [
            'name' => 'problem ' . rand(0, 1000000),
            'link' => 'http://problem.link.com/' . rand(0, 1000000),
        ];
        $response = $this->actingAs($user)->call('POST',
            '/classification/0/algorithm/' . $algorithm->id . '/problem', $problem);
        $this->assertEquals(404, $response->status());
    }
    public function testAddProblemFailWithAlgorithmNotFound()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $problem = [
            'name' => 'problem ' . rand(0, 1000000),
            'link' => 'http://problem.link.com/' . rand(0, 1000000),
        ];
        $response = $this->actingAs($user)->call('POST',
            '/classification/' . $algorithm->classification_id . '/algorithm/0/problem', $problem);
        $this->assertEquals(404, $response->status());
    }
}
