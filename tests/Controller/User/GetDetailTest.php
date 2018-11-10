<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class GetDetailTest extends TestCase
{
    public function testUserDetailSuccessSeeSelf()
    {
        $user = \App\Models\User::offset(1)->first();
        $response = $this->actingAs($user)->call('GET', '/user/' . $user->id);
        $this->assertResponseOk();
        $this->assertEquals($user->getData('detail'), $response->original);
    }
    public function testUserDetailSuccessAdminSeeOther()
    {
        $user = \App\Models\User::first();
        $user2 = \App\Models\User::offset(1)->first();
        $response = $this->actingAs($user)->call('GET', '/user/' . $user2->id);
        $this->assertResponseOk();
        $this->assertEquals($user2->getData('detail'), $response->original);
    }
    public function testUserDetailFailWithNoLogin()
    {
        $response = $this->call('GET', '/user/1');
        $this->assertEquals(401, $response->status());
    }
    public function testUserDetailFailWithNoPermission()
    {
        $user = \App\Models\User::offset(1)->first();
        $response = $this->actingAs($user)->call('GET', '/user/1');
        $this->assertEquals(403, $response->status());
    }
    public function testUserDetailFailWithNoUser()
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->call('GET', '/user/no-this-user');
        $this->assertEquals(404, $response->status());
    }
}
