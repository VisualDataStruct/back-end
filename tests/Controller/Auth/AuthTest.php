<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    public function testGetAuthSuccess()
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->call('GET', '/auth');
        $this->assertEquals(200, $response->status());
        $this->assertEquals($user->getData('detail'), $response->original);
    }
    public function testGetAuthFail()
    {
        $response = $this->call('GET', '/auth');
        $this->assertEquals(401, $response->status());
    }
}
