<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ForgetPassword extends TestCase
{
    public function testForgetPasswordSuccess()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $toEmail = 'admin@VDS.com';
        $this->call('POST', '/auth/forget', [
            'email' => $toEmail,
        ]);
        $this->assertResponseOk();
        $verify = \App\Models\Verify::where('email', '=', $toEmail)->latest()->first();
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\ForgetPasswordMail::class,
            function (\App\Mail\ForgetPasswordMail $mail)
            use ($toEmail, $verify) {
                return $mail->hasTo($toEmail)
                    && $mail->verify->verify_code === $verify->verify_code;
            });
        $response = $this->call('POST', '/auth/forget', [
            'email' => $toEmail,
        ]);
        $this->assertEquals(429, $response->status());
    }
    public function testForgetPasswordFailWithNoEmail()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $toEmail = 'no-this-email@VDS.com';
        $response = $this->call('POST', '/auth/forget', [
            'email' => $toEmail,
        ]);
        $this->assertEquals(404, $response->status());
    }
}
