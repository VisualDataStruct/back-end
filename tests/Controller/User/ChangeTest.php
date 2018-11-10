<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChangeTest extends TestCase
{
    public function testChangeUserSuccessSelf()
    {
        $user = \App\Models\User::offset(1)->first();
        $new_user_data = [
            'username' => 'testerChangeUser' . rand(0, 111111),
            'email' => 'changed-email' . rand(0, 111111) . '@VDS.com',
            'github' => 'no-this-github' . rand(0, 111111),
            'phone' => '1300000000' . rand(0, 9),
        ];
        $response = $this->actingAs($user)->call('PUT', '/user/' . $user->id, $new_user_data);
        $this->assertEquals(200, $response->status());
        $new_user_data['id'] = $user->id;
        $this->seeInDatabase('user', $new_user_data);
    }
    public function testChangeUserSuccessAdminOthers()
    {
        $admin = \App\Models\User::first();
        $user = \App\Models\User::offset(1)->first();
        $new_user_data = [
            'username' => 'testerChangeUser' . rand(0, 111111),
            'email' => 'changed-email' . rand(0, 111111) . '@VDS.com',
            'github' => 'no-this-github' . rand(0, 111111),
            'phone' => '1300000000' . rand(0, 9),
        ];
        $response = $this->actingAs($admin)->call('PUT', '/user/' . $user->id, $new_user_data);
        $this->assertEquals(200, $response->status());
        $new_user_data['id'] = $user->id;
        $this->seeInDatabase('user', $new_user_data);
    }
    public function testChangeUserFailWithNoLogin()
    {
        $new_user_data = [
            'username' => 'testerChangeUser' . rand(0, 111111),
            'email' => 'changed-email' . rand(0, 111111) . '@VDS.com',
            'github' => 'no-this-github' . rand(0, 111111),
            'phone' => '1300000000' . rand(0, 9),
        ];
        $response = $this->call('PUT', '/user/1', $new_user_data);
        $this->assertEquals(401, $response->status());
    }
    public function testChangeUserFailWithNoPermission()
    {
        $user = \App\Models\User::offset(1)->first();
        $new_user_data = [
            'username' => 'testerChangeUser' . rand(0, 111111),
            'email' => 'changed-email' . rand(0, 111111) . '@VDS.com',
            'github' => 'no-this-github' . rand(0, 111111),
            'phone' => '1300000000' . rand(0, 9),
        ];
        $response = $this->actingAs($user)->call('PUT', '/user/1', $new_user_data);
        $this->assertEquals(403, $response->status());
    }
    public function testChangeUserFailWithExistUsernameOrEmail()
    {
        $user = \App\Models\User::offset(1)->first();
        $new_user_data = [
            'username' => $user->username,
            'email' => $user->email,
        ];
        $response = $this->actingAs($user)->call('PUT', '/user/' . $user->id, $new_user_data);
        $this->assertEquals(422, $response->status());
    }
}
