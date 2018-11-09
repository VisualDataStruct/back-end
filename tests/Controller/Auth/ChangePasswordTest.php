<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChangePasswordTest extends TestCase
{
    public function testChangePasswordSuccess()
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->call('PUT', '/auth/security', [
            'old_password' => \App\Helper::sha256('admin'),
            'new_password' => \App\Helper::sha256('admin1'),
        ]);
        $this->assertResponseOk();
        $this->seeInDatabase('api_token',
            [
                'user_id' => '1',
                'token' => $response->original['token'],
            ]
        );
        $this->actingAs($user)->call('PUT', '/auth/security', [
            'old_password' => \App\Helper::sha256('admin1'),
            'new_password' => \App\Helper::sha256('admin'),
        ]);
    }
    public function testChangePasswordFailWithNotLogin()
    {
        $response = $this->call('PUT', '/auth/security', [
            'old_password' => \App\Helper::sha256('admin'),
            'new_password' => \App\Helper::sha256('admin1'),
        ]);
        $this->assertEquals(401, $response->status());
    }
    public function testChangePasswordFailWithWrongPassword()
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->call('PUT', '/auth/security', [
            'old_password' => \App\Helper::sha256('test'),
            'new_password' => \App\Helper::sha256('admin1'),
        ]);
        $this->assertEquals(401, $response->status());
        $this->assertEquals('密码错误', $response->original['message']);
    }
}
