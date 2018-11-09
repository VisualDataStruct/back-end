<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ResetPasswordTest extends TestCase
{
    public function testResetPasswordSuccess()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $toEmail = 'admin@VDS.com';
        $this->call('POST', '/auth/forget', [
            'email' => $toEmail,
        ]);
        $verify = \App\Models\Verify::where('email', '=', $toEmail)->latest()->first();

        $response = $this->call('POST', '/auth/reset', [
            'email' => $toEmail,
            'verify' => $verify->verify_code,
            'new_password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertEquals(200, $response->status());
    }
    public function testResetPasswordFailWithNoEmail()
    {
        $toEmail = 'admin@VDS.com';
        $noEmail = 'no-this-emial@VDS.com';
        $verify = \App\Models\Verify::where('email', '=', $toEmail)->latest()->first();
        $response = $this->call('POST', '/auth/reset', [
            'email' => $noEmail,
            'verify' => $verify->verify_code,
            'new_password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertEquals(404, $response->status());
        $this->assertEquals('邮箱未注册', $response->original['message']);
    }
    public function testResetPasswordFailWithNoVerify()
    {
        $user = \App\Models\User::offset(1)->first();
        $response = $this->call('POST', '/auth/reset', [
            'email' => $user->email,
            'verify' => 'tester',
            'new_password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertEquals(404, $response->status());
        $this->assertEquals('该邮箱未生成验证码', $response->original['message']);
    }
    public function testResetPasswordFailWithVerifyError()
    {
        $toEmail = 'admin@VDS.com';
        $response = $this->call('POST', '/auth/reset', [
            'email' => $toEmail,
            'verify' => '!!!!!!',
            'new_password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertEquals(401, $response->status());
        $this->assertEquals('验证码错误', $response->original['message']);
    }
    public function testResetPasswordFailWithVerifyExpired()
    {
        $toEmail = 'admin@VDS.com';
        $verify = \App\Models\Verify::where('email', '=', $toEmail)->latest()->first();
        $verify->expired_at = \Carbon\Carbon::now()->subMinute();
        $verify->save();
        $response = $this->call('POST', '/auth/reset', [
            'email' => $toEmail,
            'verify' => $verify->verify_code,
            'new_password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertEquals(401, $response->status());
        $this->assertEquals('验证码过期', $response->original['message']);
        $verify->expired_at = $verify->created_at->addMinutes(15);
        $verify->save();
    }
    public function testResetPasswordFailWithVerifyNotMatchEmail()
    {
        $toEmail = 'admin@VDS.com';
        $noEmail = 'no-this-email@VDS.com';
        $fakerVerify = new \App\Models\Verify();
        $fakerVerify->email = $noEmail;
        $fakerVerify->save();
        $response = $this->call('POST', '/auth/reset', [
            'email' => $toEmail,
            'verify' => $fakerVerify->verify_code,
            'new_password' => \App\Helper::sha256('admin'),
        ]);
        $this->assertEquals(401, $response->status());
        $this->assertEquals('验证码错误', $response->original['message']);
    }
}
