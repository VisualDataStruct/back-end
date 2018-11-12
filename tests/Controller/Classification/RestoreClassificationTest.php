<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RestoreClassificationTest extends TestCase
{
    public function testRestoreClassificationSuccess()
    {
        $user = \App\Models\User::first();
        $classification = \App\Models\Classification::onlyTrashed()->first();
        if ($classification === null) {
            $classification = \App\Models\Classification::first();
            $classification->delete();
        }
        $this->actingAs($user)->call('POST',  '/classification/' . $classification->id);
        $this->assertResponseOk();
        $this->seeInDatabase('classification', [
            'id' => $classification->id,
            'deleted_at' => null,
        ]);
    }
    public function testRestoreClassificationFailWithNotFound()
    {
        $user = \App\Models\User::first();
        $response = $this->actingAs($user)->call('POST',  '/classification/0');
        $this->assertEquals(404, $response->status());
    }
    public function testDeleteClassificationFailWithNoLogin()
    {
        $classification = \App\Models\Classification::withTrashed()->first();

        $response = $this->call('POST',  '/classification/' . $classification->id);
        $this->assertEquals(401, $response->status());
    }
}
