<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    public function testLoginSuccessWithEmail()
    {
        $response = $this->call('POST', '/auth/login', [
            'email' => 'admin@VDS.com',
            'password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertResponseOk();
        $this->seeInDatabase('api_token',
            [
                'user_id' => '1',
                'token' => $response->original['token'],
            ]
        );
    }
    public function testLoginSuccessWithUsername()
    {
        $response = $this->call('POST', '/auth/login', [
            'username' => 'administrator',
            'password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertResponseOk();
        $this->seeInDatabase('api_token',
            [
                'user_id' => '1',
                'token' => $response->original['token'],
            ]
        );
    }
    public function testLoginSuccessWithRemember()
    {
        $response = $this->call('POST', '/auth/login', [
            'email' => 'admin@VDS.com',
            'password' => \App\Helper::sha256('admin'),
            'remember' => true,
        ]);
        $this->assertResponseOk();
        $this->seeInDatabase('api_token',
            [
                'user_id' => '1',
                'token' => $response->original['token'],
            ]
        );
        $api_token = \App\Models\ApiToken::where('token', '=', $response->original['token'])->first();
        $this->assertTrue($api_token->expired_at->timestamp > \Carbon\Carbon::now()->addDays(20)->timestamp);
    }
    public function testLoginFailWithoutEmailNorUsername()
    {
        $response = $this->call('POST', '/auth/login', [
            'password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertEquals(422, $response->status());
    }
    public function testLoginFailWithoutPassword()
    {
        $response = $this->call('POST', '/auth/login', [
            'email' => 'admin@VDS.com',
        ]);
        $this->assertEquals(422, $response->status());
    }
    public function testLoginFailWithEmailNotInDatabase()
    {
        $response = $this->call('POST', '/auth/login', [
            'email' => 'notExist@unknown.com',
            'password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertEquals(404, $response->status());
    }
    public function testLoginFailWithUsernameNotInDatabase()
    {
        $response = $this->call('POST', '/auth/login', [
            'username' => 'notRegister',
            'password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertEquals(404, $response->status());
    }
    public function testLoginFailWithErrorPassword()
    {
        $response = $this->call('POST', '/auth/login', [
            'email' => 'admin@VDS.com',
            'password' => \App\Helper::sha256('admin1'),
        ]);
        $this->assertEquals(401, $response->status());
    }
}
