<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class VerifyAlgorithmTest extends TestCase
{
    public function testVerifyAlgorithmSuccess()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::where('passed', '=', 0)->first();
        $classification = $algorithm->classification()->withTrashed()->first();
        $this->actingAs($user)->call('PUT',
            '/classification/' . $classification->id . '/algorithm/' . $algorithm->id . '/verify');
        $this->assertResponseOk();
        $algorithm->pass();
        $this->seeInDatabase('algorithm', [
            'id' => $algorithm->id,
            'passed' => 1,
            'name' => $algorithm->name,
            'classification_id' => $classification->id,
            'blocksJson' => $algorithm->blocksJson,
            'blocksXml' => $algorithm->blocksXml,
            'CPlusCode' => json_encode($algorithm->CPlusCode),
        ]);
    }
    public function testVerifyFailWithNoLogin()
    {
        $algorithm = \App\Models\Algorithm::where('passed', '=', 0)->first();
        $classification = $algorithm->classification()->withTrashed()->first();
        $response = $this->call('PUT',
            '/classification/' . $classification->id . '/algorithm/' . $algorithm->id . '/verify');
        $this->assertEquals(401, $response->status());
    }
    public function testVerifyFailWithNoPermission()
    {
        $user = \App\Models\User::offset(1)->first();
        $algorithm = \App\Models\Algorithm::where('passed', '=', 0)->first();
        $classification = $algorithm->classification()->withTrashed()->first();
        $response = $this->actingAs($user)->call('PUT',
            '/classification/' . $classification->id . '/algorithm/' . $algorithm->id . '/verify');
        $this->assertEquals(403, $response->status());
    }
    public function testVerifyFailWithNotFoundClassification()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::where('passed', '=', 0)->first();
        $response = $this->actingAs($user)->call('PUT',
            '/classification/0/algorithm/' . $algorithm->id . '/verify');
        $this->assertEquals(404, $response->status());
    }
    public function testVerifyFailWithNotFoundAlgorithm()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::where('passed', '=', 0)->first();
        $classification = $algorithm->classification()->withTrashed()->first();
        $response = $this->actingAs($user)->call('PUT',
            '/classification/' . $classification->id . '/algorithm/0/verify');
        $this->assertEquals(404, $response->status());
    }
}
