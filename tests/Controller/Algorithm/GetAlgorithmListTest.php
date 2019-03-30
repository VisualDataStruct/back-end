<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class GetAlgorithmListTest extends TestCase
{
    public function testAlgorithmListSuccessWithNoLogin()
    {
        $classification = \App\Models\Classification::first();
        $algorithms = $classification->algorithms;
        $algorithmCount = 0;
        foreach ($algorithms as $algorithm) {
            if ($algorithm->isPassed) {
                $algorithmCount += 1;
            }
        }
        $response = $this->call('GET', '/classification/' . $classification->id . '/algorithm');
        $this->assertResponseOk();
        $response_algorithms = $response->original['algorithms'];
        $this->assertEquals($algorithmCount, count($response_algorithms));
        $this->assertEquals($algorithmCount, $response->original['sum']);
        foreach ($response_algorithms as $response_algorithm) {
            $response_algorithm['initVar'] = json_encode($response_algorithm['initVar']);
            $this->seeInDatabase('algorithm', $response_algorithm);
        }
    }
    public function testAlgorithmListSuccessWithLogin()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::onlyTrashed()->first();
        if ($classification === null) {
            $classification = \App\Models\Classification::first();
        }
        $algorithmCount = $classification->algorithms()->withTrashed()->count();
        $response = $this->actingAs($user)->call('GET', '/classification/' . $classification->id . '/algorithm');
        $this->assertResponseOk();
        $response_algorithms = $response->original['algorithms'];
        $this->assertEquals($algorithmCount, count($response_algorithms));
        foreach ($response_algorithms as $response_algorithm) {
            if (isset($response_algorithm['deleted_at'])) {
                unset($response_algorithm['deleted_at']);
            }
            $response_algorithm['initVar'] = json_encode($response_algorithm['initVar']);
            $this->seeInDatabase('algorithm', $response_algorithm);
        }
    }
    public function testAlgorithmListFailWithNotFoundClassification()
    {
        $classification = \App\Models\Classification::onlyTrashed()->first();
        $response = $this->call('GET', '/classification/' . ($classification->id ?? 0) . '/algorithm');
        $this->assertEquals(404, $response->status());
    }
}
