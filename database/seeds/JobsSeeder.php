<?php

use Illuminate\Database\Seeder;

class JobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //difficulties
        $difficulties = ['beginner','easy','intermediate','advanced'];
        foreach($difficulties as $difficulty){
            $dif = new \App\Models\Jobs\DifficultyLevel();
            $dif->name = $difficulty;
            $dif->created_at = date('Y-m-d H:i:s');
            $dif->save();
        }

        //categories
        $cat  = new \App\Models\Jobs\Categories();
        $cat->name='default';
        $cat->desc= 'Default category';
        $cat->save();

    }
}
