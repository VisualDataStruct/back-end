<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AddAlgorithmTest extends TestCase
{
    public function testAddAlgorithmSuccess()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::withTrashed()->first();
        $new_algorithm = [
            'name' => 'new algorithm' . rand(0, 1000000),
            'classification_id' => $classification->id,
            'blocksXml' => '<xml xmlns="http://www.w3.org/1999/xhtml"><variables><variable type="" id="6|^2K*0cdi1H!-+VS)(*">1</variable></variables><block type="variables_get" id=";pM4/qqG77O=S#^c(,]J" x="80" y="10"><field name="VAR" id="6|^2K*0cdi1H!-+VS)(*" variabletype="">1</field></block></xml>',
            'blocksJson' => '{"code":[{"block":"VAR_GET","var_name":"my_1","comment":"","comment_id":"1"}],"_var":{"my_1":0},"_sp_var":{}}',
            'initVar' => [
                [
                    'showName' => '初始链表',
                    'varName' => 'list',
                ],
            ],
            'CPlusCode' => [
                'c++ code 1',
                'c++ code 2',
                'c++ code 3',
            ],
            'tagName' => 'List',
        ];
        $response = $this->actingAs($user)->call('POST',
            '/classification/' . $classification->id . '/algorithm', $new_algorithm);
        $this->assertResponseOk();
        $new_algorithm['CPlusCode'] = json_encode($new_algorithm['CPlusCode']);
        $new_algorithm['initVar'] = json_encode($new_algorithm['initVar']);
        $new_algorithm['id'] = $response->original['id'];
        $new_algorithm['passed'] = 0;
        $this->seeInDatabase('algorithm', $new_algorithm);
    }
    public function testAddAlgorithmWithNoLogin()
    {
        $classification = \App\Models\Classification::withTrashed()->first();
        $new_algorithm = [
            'name' => 'new algorithm' . rand(0, 1000000),
            'classification_id' => $classification->id,
            'blocksXml' => '<xml xmlns="http://www.w3.org/1999/xhtml"><variables><variable type="" id="6|^2K*0cdi1H!-+VS)(*">1</variable></variables><block type="variables_get" id=";pM4/qqG77O=S#^c(,]J" x="80" y="10"><field name="VAR" id="6|^2K*0cdi1H!-+VS)(*" variabletype="">1</field></block></xml>',
            'blocksJson' => '{"code":[{"block":"VAR_GET","var_name":"my_1","comment":"","comment_id":"1"}],"_var":{"my_1":0},"_sp_var":{}}',
            'initVar' => [
                [
                    'showName' => '初始链表',
                    'varName' => 'list',
                ],
            ],
            'CPlusCode' => [
                'c++ code 1',
                'c++ code 2',
                'c++ code 3',
            ],
        ];
        $response = $this->call('POST',
            '/classification/' . $classification->id . '/algorithm', $new_algorithm);
        $this->assertEquals(401, $response->status());
    }
    public function testAddAlgorithmWithClassificationNotFound()
    {
        $user = \App\Models\User::first();
        $new_algorithm = [
            'name' => 'new algorithm' . rand(0, 1000000),
            'classification_id' => 0,
            'blocksXml' => '<xml xmlns="http://www.w3.org/1999/xhtml"><variables><variable type="" id="6|^2K*0cdi1H!-+VS)(*">1</variable></variables><block type="variables_get" id=";pM4/qqG77O=S#^c(,]J" x="80" y="10"><field name="VAR" id="6|^2K*0cdi1H!-+VS)(*" variabletype="">1</field></block></xml>',
            'blocksJson' => '{"code":[{"block":"VAR_GET","var_name":"my_1","comment":"","comment_id":"1"}],"_var":{"my_1":0},"_sp_var":{}}',
            'initVar' => [
                [
                    'showName' => '初始链表',
                    'varName' => 'list',
                ],
            ],
            'CPlusCode' => [
                'c++ code 1',
                'c++ code 2',
                'c++ code 3',
            ],
        ];
        $response = $this->actingAs($user)->call('POST',
            '/classification/0/algorithm', $new_algorithm);
        $this->assertEquals(404, $response->status());
    }
    public function testAddAlgorithmWithErrorForm()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::withTrashed()->first();
        $new_algorithm = [
            'name' => 'test',
        ];
        $response = $this->actingAs($user)->call('POST',
            '/classification/' . $classification->id . '/algorithm', $new_algorithm);
        $this->assertEquals(422, $response->status());
    }
    public function testAddAlgorithmWithSameName()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::withTrashed()->first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $new_algorithm = [
            'name' => $algorithm->name,
            'classification_id' => 0,
            'blocksXml' => '<xml xmlns="http://www.w3.org/1999/xhtml"><variables><variable type="" id="6|^2K*0cdi1H!-+VS)(*">1</variable></variables><block type="variables_get" id=";pM4/qqG77O=S#^c(,]J" x="80" y="10"><field name="VAR" id="6|^2K*0cdi1H!-+VS)(*" variabletype="">1</field></block></xml>',
            'blocksJson' => '{"code":[{"block":"VAR_GET","var_name":"my_1","comment":"","comment_id":"1"}],"_var":{"my_1":0},"_sp_var":{}}',
            'initVar' => [
                [
                    'showName' => '初始链表',
                    'varName' => 'list',
                ],
            ],
            'CPlusCode' => [
                'c++ code 1',
                'c++ code 2',
                'c++ code 3',
            ],
        ];
        $response = $this->actingAs($user)->call('POST',
            '/classification/' . $classification->id . '/algorithm', $new_algorithm);
        $this->assertEquals(422, $response->status());
    }
}
