<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChangeClassificationListTest extends TestCase
{
    public function testChangeClassificationSuccess()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::first();
        $test_classification = [
            'name' => \PascalDeVink\ShortUuid\ShortUuid::uuid4(),
            'description' => 'test description' . \PascalDeVink\ShortUuid\ShortUuid::uuid4(),
        ];
        $this->actingAs($user)->call('PUT',  '/classification/' . $classification->id, $test_classification);
        $this->assertResponseOk();
        $test_classification['id'] = $classification->id;
        $this->seeInDatabase('classification', $test_classification);
    }
    public function testChangeClassificationFailWithNoLogin()
    {
        $test_classification = [
            'name' => 'test',
            'description' => 'test description',
        ];
        $classification = \App\Models\Classification::first();
        $response = $this->call('PUT', '/classification/' . $classification->id, $test_classification);
        $this->assertEquals(401, $response->status());
    }
    public function testChangeClassificationFailWithExistName()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::first();
        $secondClassification = \App\Models\Classification::offset(1)->first();
        $test_classification = [
            'name' => $secondClassification->name,
            'description' => 'test description',
        ];
        $response = $this->actingAs($user)->call('PUT', '/classification/' . $classification->id, $test_classification);
        $this->assertEquals(422, $response->status());
    }
    public function testChangeClassificationFailWithNotFound()
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->call('PUT', '/classification/0', [
            'name' => 'test',
            'description' => 'test description',
        ]);
        $this->assertEquals(404, $response->status());
    }
}
