<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChangeAlgorithmTest extends TestCase
{
    public function testChangeAlgorithmSuccess()
    {
        $user = \App\Models\User::offset(1)->first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $classification = $algorithm->classification()->withTrashed()->first();
        $new_algorithm = [
            'name' => 'new algorithm' . rand(0, 1000000),
            'classification_id' => $classification->id,
            'blocksXml' => '<xml xmlns="http://www.w3.org/1999/xhtml"><variables><variable type="" id="6|^2K*0cdi1H!-+VS)(*">1</variable></variables><block type="variables_get" id=";pM4/qqG77O=S#^c(,]J" x="80" y="10"><field name="VAR" id="6|^2K*0cdi1H!-+VS)(*" variabletype="">1</field></block></xml>',
            'blocksJson' => '{"code":[{"block":"VAR_GET","var_name":"my_1","comment":"","comment_id":"1"}],"_var":{"my_1":0},"_sp_var":{}}',
            'CPlusCode' => [
                'c++ code ' . rand(0, 1000000),
                'c++ code ' . rand(0, 1000000),
                'c++ code ' . rand(0, 1000000),
            ],
        ];
        $this->actingAs($user)->call('PUT',
            '/classification/' . $classification->id . '/algorithm/' . $algorithm->id, $new_algorithm);
        $this->assertResponseOk();
        $new_algorithm['CPlusCode'] = json_encode($new_algorithm['CPlusCode']);
        $new_algorithm['id'] = $algorithm->id;
        $new_algorithm['passed'] = $algorithm->passed;
        $this->seeInDatabase('algorithm', $new_algorithm);
    }
    public function testChangeAlgorithmFailWithNoLogin()
    {
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $classification = $algorithm->classification()->withTrashed()->first();
        $response = $this->call('PUT',
            '/classification/' . $classification->id . '/algorithm/' . $algorithm->id, $algorithm->getData('detail'));
        $this->assertEquals(401, $response->status());
    }
    public function testChangeAlgorithmFailWithClassificationNotFound()
    {
        $user = \App\Models\User::offset(1)->first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $response = $this->actingAs($user)->call('PUT',
            '/classification/0/algorithm/' . $algorithm->id, $algorithm->getData('detail'));
        $this->assertEquals(404, $response->status());
    }
    public function testChangeAlgorithmFailWithAlgorithmNotFound()
    {
        $user = \App\Models\User::offset(1)->first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $classification = $algorithm->classification()->withTrashed()->first();
        $response = $this->actingAs($user)->call('PUT',
            '/classification/' . $classification->id . '/algorithm/0', $algorithm->getData('detail'));
        $this->assertEquals(404, $response->status());
    }
    public function testChangeAlgorithmFailWithNameExist()
    {
        $user = \App\Models\User::offset(1)->first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $classification = $algorithm->classification()->withTrashed()->first();
        $response = $this->actingAs($user)->call('PUT',
            '/classification/' . $classification->id . '/algorithm/' . $algorithm->id, [
                'name' => $algorithm->name,
            ]);
        $this->assertEquals(422, $response->status());
    }
}
