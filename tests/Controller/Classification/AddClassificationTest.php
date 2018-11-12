<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AddClassificationListTest extends TestCase
{
    public function testAddClassificationSuccess()
    {
        $user = \App\Models\User::first();
        $test_classification = [
            'name' => \PascalDeVink\ShortUuid\ShortUuid::uuid4(),
            'description' => 'test description' . \PascalDeVink\ShortUuid\ShortUuid::uuid4(),
        ];
        $response = $this->actingAs($user)->call('POST', '/classification', $test_classification);
        $this->assertResponseOk();
        $test_classification['id'] = $response->original['id'];
        $this->seeInDatabase('classification', $test_classification);
    }
    public function testAddClassificationFailWithNoLogin()
    {
        $test_classification = [
            'name' => 'test',
            'description' => 'test description',
        ];
        $response = $this->call('POST', '/classification', $test_classification);
        $this->assertEquals(401, $response->status());
    }
    public function testAddClassificationFailWithExistName()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::first();
        $test_classification = [
            'name' => $classification->name,
            'description' => 'test description',
        ];
        $response = $this->actingAs($user)->call('POST', '/classification', $test_classification);
        $this->assertEquals(422, $response->status());
    }
}
