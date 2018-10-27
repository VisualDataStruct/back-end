<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = $this->getUsers(10);
        foreach ($users as $user) {
            $user->save();
        }
        $classifications = $this->getClassifications(5);
        foreach ($classifications as $classification) {
            $classification->save();
            $algorithms = $this->getAlgorithms(5);
            foreach ($algorithms as $algorithm) {
                $classification->algorithms()->save($algorithm);
            }
        }
    }

    /**
     * @param int $num
     * @return \App\Models\User[]
     */
    private function getUsers(int $num)
    {
        return factory(\App\Models\User::class, $num)->make();
    }

    /**
     * @param int $num
     * @return \App\Models\Classification[]
     */
    private function getClassifications(int $num)
    {
        return factory(\App\Models\Classification::class, $num)->make();
    }

    /**
     * @param int $num
     * @return \App\Models\Algorithm[]
     */
    private function getAlgorithms(int $num)
    {
        return factory(\App\Models\Algorithm::class, $num)->make();
    }
}
