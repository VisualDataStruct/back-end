<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DeleteProblemTest extends TestCase
{
    public function testDeleteProblemSuccessWithLink()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $problem = [
            'name' => 'test delete 1',
            'link' => 'http://test.delete.problem.com/1',
        ];
        $algorithm->addProblem($problem['name'], $problem['link']);
        $algorithm->addProblem($problem['name'], $problem['link'] . '1');
        $algorithm->save();
        $this->actingAs($user)->call('DELETE',
            '/classification/' . $algorithm->classification_id . '/algorithm/' . $algorithm->id . '/problem', [
                'name' => $problem['name'],
                'link' => $problem['link'],
            ]);
        $this->assertResponseOk();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $pros = $algorithm->problems;
        foreach ($pros as $key => $pro) {
            $pros[$key] = json_encode($pro);
        }
        $this->assertNotContains(json_encode($problem), $pros);
        $problem['link'] = $problem['link'] . '1';
        $this->assertContains(json_encode($problem), $pros);
    }
    public function testDeleteProblemSuccessWithNoLink()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $problem = [
            'name' => 'test delete 1',
            'link' => 'http://test.delete.problem.com/1',
        ];
        $algorithm->addProblem($problem['name'], $problem['link']);
        $algorithm->addProblem($problem['name'], $problem['link'] . '1');
        $algorithm->save();
        $this->actingAs($user)->call('DELETE',
            '/classification/' . $algorithm->classification_id . '/algorithm/' . $algorithm->id . '/problem', [
                'name' => $problem['name'],
            ]);
        $this->assertResponseOk();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $pros = $algorithm->problems;
        foreach ($pros as $key => $pro) {
            $pros[$key] = json_encode($pro);
        }
        $this->assertNotContains(json_encode($problem), $pros);
        $problem['link'] = $problem['link'] . '1';
        $this->assertNotContains(json_encode($problem), $pros);
    }
    public function testDeleteProblemFailWithNoLogin()
    {
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $response = $this->call('DELETE',
            '/classification/' . $algorithm->classification_id . '/algorithm/' . $algorithm->id . '/problem', [
                'name' => 'test',
                'link' => 'test link',
            ]);
        $this->assertEquals(401, $response->status());
    }
    public function testDeleteProblemFailWithClassificationNotFound()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $response = $this->actingAs($user)->call('DELETE',
            '/classification/0/algorithm/' . $algorithm->id . '/problem', [
                'name' => 'test',
                'link' => 'test link',
            ]);
        $this->assertEquals(404, $response->status());
    }
    public function testDeleteProblemFailWithAlgorithmNotFound()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $response = $this->actingAs($user)->call('DELETE',
            '/classification/' . $algorithm->classification_id . '/algorithm/0/problem', [
                'name' => 'test',
                'link' => 'test link',
            ]);
        $this->assertEquals(404, $response->status());
    }
    public function testDeleteProblemFailWithNoName()
    {
        $user = \App\Models\User::first();
        $algorithm = \App\Models\Algorithm::withTrashed()->first();
        $response = $this->actingAs($user)->call('DELETE',
            '/classification/' . $algorithm->classification_id . '/algorithm/' . $algorithm->id . '/problem', [
                'link' => 'test link',
            ]);
        $this->assertEquals(422, $response->status());
    }
}
