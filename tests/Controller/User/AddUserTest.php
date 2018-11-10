<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AddUserTest extends TestCase
{
    public function testAddUserSuccess()
    {
        \Illuminate\Support\Facades\Mail::fake();

        $user = \App\Models\User::first();
        $new_user_data = [
            'username' => 'testerAddUser' . rand(0, 111111),
            'realName' => '查无此人',
            'email' => 'no-this-email' . rand(0, 111111) . '@VDS.com',
            'github' => 'no-this-github',
            'phone' => '13900000000',
        ];
        $response = $this->actingAs($user)->call('POST', '/user/add', $new_user_data);
        $this->assertEquals(200, $response->status());
        $new_user_data['id'] = $response->original['id'];
        $this->seeInDatabase('user', $new_user_data);
        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\NewUserMail::class,
            function (\App\Mail\NewUserMail $mail) use ($new_user_data) {
                return $mail->hasTo($new_user_data['email'])
                    && $mail->username === $new_user_data['username'];
            });
    }
    public function testAddUserFailWithNoLogin()
    {
        $new_user_data = [
            'username' => 'testerAddUser' . rand(0, 111111),
            'realName' => '查无此人',
            'email' => 'no-this-email' . rand(0, 111111) . '@VDS.com',
            'github' => 'no-this-github',
            'phone' => '13900000000',
        ];
        $response = $this->call('POST', '/user/add', $new_user_data);
        $this->assertEquals(401, $response->status());
    }
    public function testAddUserFailWithNoPermission()
    {
        $user = \App\Models\User::offset(1)->first();
        $new_user_data = [
            'username' => 'testerAddUser' . rand(0, 111111),
            'realName' => '查无此人',
            'email' => 'no-this-email' . rand(0, 111111) . '@VDS.com',
            'github' => 'no-this-github',
            'phone' => '13900000000',
        ];
        $response = $this->actingAs($user)->call('POST', '/user/add', $new_user_data);
        $this->assertEquals(403, $response->status());
    }
    public function testAddUserFailWithExistEmailAndUsername()
    {
        $user = \App\Models\User::first();
        $new_user_data = [
            'username' => $user->username,
            'realName' => $user->realName,
            'email' => $user->email,
            'github' => $user->github,
            'phone' => $user->phone,
        ];
        $response = $this->actingAs($user)->call('POST', '/user/add', $new_user_data);
        $this->assertEquals(422, $response->status());
    }
}
