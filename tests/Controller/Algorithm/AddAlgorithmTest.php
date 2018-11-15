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
            'pseudoCode' => [
                '伪代码 1',
                '伪代码 2',
                '伪代码 3',
            ],
            'jsCode' => [
                'js code 1',
                'js code 2',
                'js code 3',
            ],
            'explain' => [
                'explain 1',
                'explain 2',
                'explain 3',
            ],
            'CPlusCode' => [
                'c++ code 1',
                'c++ code 2',
                'c++ code 3',
            ],
        ];
        $response = $this->actingAs($user)->call('POST',
            '/classification/' . $classification->id . '/algorithm', $new_algorithm);
        $this->assertResponseOk();
        $new_algorithm['pseudoCode'] = json_encode($new_algorithm['pseudoCode']);
        $new_algorithm['jsCode'] = json_encode($new_algorithm['jsCode']);
        $new_algorithm['explain'] = json_encode($new_algorithm['explain']);
        $new_algorithm['CPlusCode'] = json_encode($new_algorithm['CPlusCode']);
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
            'pseudoCode' => [
                '伪代码 1',
                '伪代码 2',
                '伪代码 3',
            ],
            'jsCode' => [
                'js code 1',
                'js code 2',
                'js code 3',
            ],
            'explain' => [
                'explain 1',
                'explain 2',
                'explain 3',
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
            'pseudoCode' => [
                '伪代码 1',
                '伪代码 2',
                '伪代码 3',
            ],
            'jsCode' => [
                'js code 1',
                'js code 2',
                'js code 3',
            ],
            'explain' => [
                'explain 1',
                'explain 2',
                'explain 3',
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
}
