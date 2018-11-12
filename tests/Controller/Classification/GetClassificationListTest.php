<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class GetClassificationListTest extends TestCase
{
    public function testClassificationListSuccessWithNoLogin()
    {
        $classificationCount = \App\Models\Classification::query()->count();
        $response = $this->call('GET', '/classification');
        $this->assertResponseOk();
        $response_classifications= $response->original;
        $this->assertEquals($classificationCount, count($response_classifications));
        foreach ($response_classifications as $response_classification) {
            $this->seeInDatabase('classification', $response_classification);
        }
    }
    public function testClassificationListSuccessWithLogin()
    {
        $user = \App\Models\User::first();
        $classificationCount = \App\Models\Classification::withTrashed()->count();
        $response = $this->actingAs($user)->call('GET', '/classification');
        $this->assertResponseOk();
        $response_classifications= $response->original;
        $this->assertEquals($classificationCount, count($response_classifications));
        foreach ($response_classifications as $response_classification) {
            if (isset($response_classification['deleted_at'])) {
                unset($response_classification['deleted_at']);
            }
            $this->seeInDatabase('classification', $response_classification);
        }
    }
}
