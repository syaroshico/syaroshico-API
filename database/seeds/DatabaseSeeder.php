<?php

use App\Http\Controllers\CountApiController;
use App\Tools\Options\Options;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call('UserTableSeeder');
        $this->call('OptionsTableSeeder');

        Model::reguard();
    }
}

class OptionsTableSeeder extends Seeder {
    const COUNT_FORMAT = 'count[%s]' ;
    const LATEST_FORMAT ='latest[%s]';
    public function run() {
        DB::table('options')->delete();

        // Hard code know value (2013~2015-12-07)

        /*
         * Both count
         */
        Options::set(sprintf(self::COUNT_FORMAT, CountApiController::BOTH_QUERY_VALUE),'10820');
        Options::set(sprintf(self::LATEST_FORMAT, CountApiController::BOTH_QUERY_VALUE),'673486754981154816');

        /*
         * URL count
         */
        Options::set(sprintf(self::COUNT_FORMAT, CountApiController::URL_QUERY_VALUE),'710');
        Options::set(sprintf(self::LATEST_FORMAT, CountApiController::URL_QUERY_VALUE),'673481158164811776');

        /*
         * Syaroshico count
         */
        Options::set(sprintf(self::COUNT_FORMAT, CountApiController::SYAROSHICO_SEARCH_VALUE),'10817');
        Options::set(sprintf(self::LATEST_FORMAT, CountApiController::SYAROSHICO_SEARCH_VALUE),'673789641200308224');

    }
}

