<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class GetAlgorithmDetailTest extends TestCase
{
    public function testGetAlgorithmSuccessWithNoLogin()
    {
        $classification = \App\Models\Classification::first();
        $algorithm = $classification->algorithms()->first();
        $algorithm->pass();
        $algorithm->save();
        $classification->getSum();
        $classification->save();
        $response = $this->call('GET', '/classification/' . $classification->id . '/algorithm/' . $algorithm->id);
        $this->assertResponseOk();
        $this->assertEquals($algorithm->getData('detail'), $response->original);
    }
    public function testGetAlgorithmSuccessWithLogin()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::first();
        $algorithm = $classification->algorithms()->offset(1)->first();
        $response = $this->actingAs($user)->call('GET', '/classification/' . $classification->id . '/algorithm/' . $algorithm->id);
        $this->assertResponseOk();
        $this->assertEquals($algorithm->getData('detail'), $response->original);
    }
    public function testGetAlgorithmFailWithClassificationNotFound()
    {
        $classification = \App\Models\Classification::onlyTrashed()->first();
        $response = $this->call('GET', '/classification/' . ($classification->id ?? 0) . '/algorithm/0');
        $this->assertEquals(404, $response->status());
    }
    public function testGetAlgorithmFailWithAlgorithmNotFound()
    {
        $user= \App\Models\User::first();
        $classification = \App\Models\Classification::first();
        $response = $this->actingAs($user)->call('GET', '/classification/' . $classification->id . '/algorithm/0');
        $this->assertEquals(404, $response->status());
    }
}
