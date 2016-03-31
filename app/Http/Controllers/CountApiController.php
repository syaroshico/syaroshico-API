<?php

namespace App\Http\Controllers;

use App\Tools\Options\Options;
use App\Tools\Twitter\SearchWalkCount;
use Illuminate\Http\Request;
use \Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;
use TwitterOAuth\Exception\TwitterException;

class CountApiController extends BaseController
{

    const BOTH_QUERY_VALUE = 'syaroshi.co OR シャロシコ exclude:retweets';
    const URL_QUERY_VALUE = 'syaroshi.co exclude:retweets';
    const SYAROSHICO_SEARCH_VALUE = 'シャロシコ exclude:retweets';
    const CHINOSHICO_SEARCH_VALUE = 'チノシコ exclude:retweets';

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function count( Request $request )
    {
        try {
            $include_uri   = $request->input( 'url' );
            $include_texts = $request->input( 'shico' );
            $force_cache   = $request->input( 'force_cache' );
            $is_chino      = $request->input( 'chino' );
            if ( ! is_null( $is_chino ) ) {
                $keyword = self::CHINOSHICO_SEARCH_VALUE;
            } else if ( ( is_null( $include_uri ) && is_null( $include_texts ) ) or ( $include_uri && $include_texts ) ) {
                $keyword = self::BOTH_QUERY_VALUE;
            } else if ( $include_uri && ! $include_texts ) {
                $keyword = self::URL_QUERY_VALUE;
            } else if ( $include_texts && ! $include_uri ) {
                $keyword = self::SYAROSHICO_SEARCH_VALUE;
            } else {
                throw new \InvalidArgumentException( 'most least one selector selected' );
            }
            if ( (bool) Options::get( "lock[$keyword]", false ) ) {
                throw new \RuntimeException( 'Querying... (sorry wait a few minutes and retry access)' );
            }
            $old_updated = (int) Options::get( "updated[$keyword]", 0 );
            if ( ( time() < ( $old_updated ) + ( 60 * 2 ) ) || (bool) $force_cache ) {
                $count = [
                    'count'   => (int) Options::get( "count[$keyword]", 0 ),
                    'max_id'  => Options::get( "latest[$keyword]" ),
                    'queried' => 'cache',
                ];
            } else {

                Options::set( "updated[$keyword]", time() );
                $count = SearchWalkCount::countSearch( $keyword, function ( SearchWalkCount $swc ) {

                } );
            }

            $res = [
                'count'     => $count['count'],
                'max_id'    => $count['max_id'],
                'query'     => $keyword,
                'queried'   => $count['queried'],
                'timestamp' => Options::get( "updated[$keyword]" ),
            ];

            return response()->json( $res )->header( 'Access-Control-Allow-Origin', '*' );
        } catch ( TwitterException $ex ) {
            try {
                if ( ! isset( $count ) ) {
                    if ( $keyword ) {
                        $count = [
                            'count' => Options::get( "count[$keyword]" )
                        ];
                    } else {
                        $count = - 1;
                    }
                }
                if ( isset( $old_updated ) && isset( $keyword ) ) {
                    Options::set( "updated[$keyword]", $old_updated );
                }

            } catch ( \Exception $e ) {
                $count = [ 'count' => - 2 ];
            }
            $res = [
                'error'   => true,
                'count'   => isset( $count['count'] ) ? $count['count'] : 0,
                'message' => 'Twitter API Error: ' . $ex->getMessage(),
            ];

            return response()->json( $res )->setStatusCode( 500 )->header( 'Access-Control-Allow-Origin', '*' );


        } catch ( \Exception $ex ) {
            $res = [
                'error'   => true,
                'count'   => - 4,
                'message' => $ex->getMessage(),
            ];
            try {
                if ( isset( $old_updated ) && isset( $keyword ) ) {
                    Options::set( "updated[$keyword]", $old_updated );
                }
            } catch ( \Exception $e ) {
            }


            return response()->json( $res )->setStatusCode( 500 )->header( 'Access-Control-Allow-Origin', '*' );


        }

    }


}