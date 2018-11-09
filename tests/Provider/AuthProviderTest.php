<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthProviderTest extends TestCase
{
    public function testTokenExpired()
    {
        $response = $this->call('POST', '/auth/login', [
            'email' => 'admin@VDS.com',
            'password' => \App\Helper::sha256('admin'),
        ]);
        $api_token = \App\Models\ApiToken::where('token', '=', $response->original['token'])->first();
        $api_token->expired_at = \Carbon\Carbon::now()->subMinute();
        $api_token->save();
        $response = $this->call('PUT', '/auth/security', [
            'token' => $api_token->token,
            'old_password' => \App\Helper::sha256('admin'),
            'new_password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertEquals(401, $response->status());

    }
    public function testTokenAddHour()
    {
        $response = $this->call('POST', '/auth/login', [
            'email' => 'admin@VDS.com',
            'password' => \App\Helper::sha256('admin'),
        ]);
        $api_token = \App\Models\ApiToken::where('token', '=', $response->original['token'])->first();
        $this->call('PUT', '/auth/security', [
            'token' => $api_token->token,
            'old_password' => \App\Helper::sha256('admin'),
            'new_password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertResponseOk();
        $this->assertTrue($api_token->expired_at->timestamp > \Carbon\Carbon::now()->addMinutes(50)->timestamp);
        $this->assertTrue($api_token->expired_at->timestamp < \Carbon\Carbon::now()->addDays(2)->timestamp);
    }
    public function testTokenAddMonth()
    {
        $response = $this->call('POST', '/auth/login', [
            'email' => 'admin@VDS.com',
            'password' => \App\Helper::sha256('admin'),
            'remember' => true,
        ]);
        $api_token = \App\Models\ApiToken::where('token', '=', $response->original['token'])->first();
        $this->call('PUT', '/auth/security', [
            'token' => $api_token->token,
            'old_password' => \App\Helper::sha256('admin'),
            'new_password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertResponseOk();
        $this->assertTrue($api_token->expired_at->timestamp > \Carbon\Carbon::now()->addDays(20)->timestamp);
    }
}
