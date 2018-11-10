<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class GetListTest extends TestCase
{
    public function testUserListSuccess()
    {
        $userNumber = \App\Models\User::query()->count();
        $response = $this->call('GET', '/user');
        $this->assertResponseOk();
        $response_users = $response->original;
        $this->assertEquals($userNumber, count($response_users));
        foreach ($response_users as $response_user) {
            $this->seeInDatabase('user', $response_user);
        }
    }
}
