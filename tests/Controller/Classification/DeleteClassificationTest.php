<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DeleteClassificationListTest extends TestCase
{
    public function testDeleteClassificationSuccess()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::first();
        $this->actingAs($user)->call('DELETE',  '/classification/' . $classification->id);
        $this->assertResponseOk();
        $this->notSeeInDatabase('classification', [
            'id' => $classification->id,
            'deleted_at' => null,
        ]);
    }
    public function testDeleteClassificationFailWithNotFound()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::onlyTrashed()->first();
        $response = $this->actingAs($user)->call('DELETE',  '/classification/' . $classification->id);
        $this->assertEquals(404, $response->status());
    }
    public function testDeleteClassificationFailWithNoLogin()
    {
        $classification = \App\Models\Classification::first();
        $response = $this->call('DELETE',  '/classification/' . $classification->id);
        $this->assertEquals(401, $response->status());
    }
}
