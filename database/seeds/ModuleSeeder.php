<?php

use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules  = ['users','profile','jobs','job-categories','job-applications','jobs-manager',
            'job-skills','application-message','payouts','logs'];
        foreach($modules as $module){
            \App\Models\Modules::create(
                [
                    'name'=>$module
                ]
            );
        }

    }
}
