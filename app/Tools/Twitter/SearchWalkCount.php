<?php

namespace App\Tools\Twitter;


use App\Tools\Options\Options;

use TwitterOAuth\Auth\AuthAbstract;

class SearchWalkCount {

    const COUNT = 100;
    const MODE_BACK = 'back';
    const MODE_RECENT = 'recent';
    const LANG = 'ja';

    /** @var  string */
    public $keyword;
    /** @var string */
    public $mode;
    /** @var int */
    public $count = 0;
    /** @var  OAuthWrapper */
    public $twitterAuth;
    /** @var  AuthAbstract */
    public $twitterOAuth;


    /**
     * SearchWalkCount constructor.
     *
     * @param $keyword
     * @param null $sinceId
     */
    public function __construct( $keyword, $sinceId = null ) {
        $this->keyword     = $keyword;
        $this->mode        = is_null( $sinceId ) ? self::MODE_BACK : self::MODE_RECENT;
        $this->twitterAuth = new OAuthWrapper( new TwitterAppCredentials( getenv( 'TW_CKEY' ), getenv( 'TW_CSECRET' ) ) );
        $this->twitterAuth->autoAuth();
        $this->twitterOAuth = $this->twitterAuth->client;

    }


    /**
     * @param $keyword
     * @param $param
     *
     * @return TwitterSearchResult
     */
    private function search( $keyword, $param ) {
        $params            = [
            'q'                => $keyword,
            'count'            => self::COUNT,
            'lang'             => self::LANG,
            'result_type'      => 'recent',
            'include_entities' => 'false',

        ];
        $params            = array_merge( $params, $param );
        $res               = new TwitterSearchResult( $this->twitterOAuth->get( 'search/tweets', $params ) );
        $res->meta->max_id = $res->search_metadata->max_id_str;
        parse_str( trim( $res->search_metadata->next_results, '?' ), $next );
        $res->meta->next_id = isset( $next['max_id'] ) ? $next['max_id'] : null;

        return $res;

    }

    /**
     * @param $keyword
     * @param callable|null $callback
     *
     * @return array
     */
    public static function countSearch( $keyword, $callback = null ) {
        $since_id   = Options::get( "latest[$keyword]" );
        $count      = (int) Options::get( "count[$keyword]", 0 );
        $swc        = new self( $keyword, $since_id );
        $swc->count = $count;
        if ( is_callable( $callback ) ) {

            call_user_func( $callback, $swc );
        }
        $res = $swc->autoSearchCount( $since_id );
        Options::set( "latest[$keyword]", $res['max_id'] );
        Options::set( "count[$keyword]", $res['count'] );
        Options::set( "updated[$keyword]", time() );

        return $res;


    }

    /**
     * @param string|null $max_id
     *
     * @return array
     */
    public function searchCountToOld( $max_id = null ) {
        $count      = 0;
        $i          = 0;
        $next_param = [ ];
        /*        if ( isset( $next_param['max_id'] ) ) {
                    printf( '<h2>MAX:%s</h2>', $next_param['max_id'] );
                }*/
        /** @var TwitterSearchResult $tweets */
        while ( ( $tweets = $this->search( $this->keyword, $next_param ) )->statuses ) {
            $count                 = $count + count( $tweets->statuses );
            $last                  = end( $tweets->statuses );
            $next_param ['max_id'] = is_null( $tweets->meta->next_id ) ? $tweets->meta->next_id : $last->id_str;

            set_time_limit( 40 );

//            foreach ( $tweets->statuses as $tweet ) {
////                printf( '<p>@%s: %s @%s</p>', $tweet->user->screen_name, $tweet->text, $tweet->created_at );
//            }

            set_time_limit( 40 );


            if ( 0 === $i ) {
                $max_id = $tweets->meta->max_id;
                Options::set( sprintf( 'latest[%s]', $this->keyword ), $max_id );
                Options::set( sprintf( 'updated[%s]', $this->keyword ), time() );
            }
            Options::set( sprintf( 'count[%s]', $this->keyword ), $count );
            $i ++;
            set_time_limit( 40 );
            if ( is_null( $tweets->meta->next_id ) ) {
                if ( isset( $until ) ) {

                    /** @var \DateTime $until */
                    $until->modify( '-1 day' );
                } else {
                    $until = new \DateTime( $last->created_at );
//                    $until = $until->modify( '+1 day' );
                }
                $next_param['q']      = $this->keyword . ' until:' . $until->format( 'Y-m-d' );
                $next_param ['until'] = $until->format( 'Y-m-d' );
//                printf( '<h1>Until: %s</h1>', $next_param["until"] );
            }

        }

        return [
            'count'   => $count,
            'max_id'  => $max_id,
            'queried' => 'full',
        ];

    }

    /**
     * @param string $since_id
     *
     * @return array
     */
    public function searchCountSince( $since_id ) {
        $count      = $this->count;
        $i          = 0;
        $max_id     = $since_id;
        $next_param = [
            'since_id' => $since_id,
        ];


        /** @var TwitterSearchResult $tweets */
        while ( ( $tweets = $this->search( $this->keyword, $next_param ) )->statuses ) {
            $i ++;
            set_time_limit( 40 );
            $c = count( $tweets->statuses );
            $count += $c;
            if ( 1 === $i ) {
                $max_id = $tweets->meta->max_id;
            }
            if ( $c === self::COUNT && ! is_null( $tweets->meta->next_id ) ) {
                $next_param ['max_id'] = $tweets->meta->next_id;
            } else {
                break;
            }


        }

        return [
            'count'   => $count,
            'max_id'  => $max_id,
            'queried' => 'diff',
        ];


    }

    /**
     * @param string|null $sinceId
     *
     * @return array
     */
    public function autoSearchCount( $sinceId = null ) {
        if ( self::MODE_BACK === $this->mode ) {
            ignore_user_abort( true );
            Options::set( "lock[$this->keyword]", true );
            $res = $this->searchCountToOld();
            Options::remove( "lock[$this->keyword]" );
            ignore_user_abort( false );

            return $res;
        } else {
            return $this->searchCountSince( $sinceId );
        }
    }

    public function test() {
        header( 'Content-Type: text/plain;' );

        return var_export( [
            $r = $this->search( $this->keyword, [
                'max_id'   => '673281797938020352',
                'since_id' => '673272810874138624'
            ] ),
            count( $r->statuses )
        ] );

    }
}