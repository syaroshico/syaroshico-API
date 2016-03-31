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
        $this->call( 'OptionsTableSeeder' );

        Model::reguard();
    }
}

class OptionsTableSeeder extends Seeder
{
    const COUNT_FORMAT = 'count[%s]';
    const LATEST_FORMAT = 'latest[%s]';

    public function run()
    {
        DB::table( 'options' )->delete();

        // Hard code know value (2013~2015-12-07)

        /*
         * Both count
         */
        Options::set( sprintf( self::COUNT_FORMAT, CountApiController::BOTH_QUERY_VALUE ), '23021' );
        Options::set( sprintf( self::LATEST_FORMAT, CountApiController::BOTH_QUERY_VALUE ), '715571766265942016' );

        /*
         * URL count
         */
        Options::set( sprintf( self::COUNT_FORMAT, CountApiController::URL_QUERY_VALUE ), '1778' );
        Options::set( sprintf( self::LATEST_FORMAT, CountApiController::URL_QUERY_VALUE ), '715573460789911552' );

        /*
         * Syaroshico count
         */
        Options::set( sprintf( self::COUNT_FORMAT, CountApiController::SYAROSHICO_SEARCH_VALUE ), '22338' );
        Options::set( sprintf( self::LATEST_FORMAT, CountApiController::SYAROSHICO_SEARCH_VALUE ), '715573878886563840' );

        /*
         * ChinoShico count
         */
        Options::set( sprintf( self::COUNT_FORMAT, CountApiController::CHINOSHICO_SEARCH_VALUE ), '1955' );
        Options::set( sprintf( self::LATEST_FORMAT, CountApiController::CHINOSHICO_SEARCH_VALUE ), '715566258679209984' );


    }
}

