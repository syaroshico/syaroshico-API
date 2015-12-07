<?php

namespace App\Tools\Twitter;


use App\Tools\Options\Options;
use TwitterOAuth\Auth\ApplicationOnlyAuth;
use TwitterOAuth\Auth\AuthAbstract;
use TwitterOAuth\Serializer\ObjectSerializer;

class OAuthWrapper {
    /** @var AuthAbstract */
    protected $_twitteroauth;

    public function __construct( TwitterAppCredentials $credentials = null ) {
        if ( is_null( $credentials ) or ! $credentials->consumer_secret or ! $credentials->consumer_key ) {
            $credentials = new TwitterAppCredentials( env( 'TW_CKEY' ), env( 'TW_CSECRET' ) );
        }

        $serializer          = new ObjectSerializer();
        $this->_twitteroauth = new ApplicationOnlyAuth( (array) $credentials, $serializer );
    }

    public function __get( $name ) {
        switch ( $name ) {
            case "client":
                return $this->_twitteroauth;
                break;
            default:
                throw new \OutOfRangeException( 'Invalid property: ' . $name );
        }
    }

    /**
     * @param string $token Bearer token
     */
    public function setToken( $token ) {
        $this->_twitteroauth->setBearerToken( $token );

        return $this;
    }

    public function autoAuth() {
        $token = Options::get( 'bearerToken' );
        if ( $token ) {
            $this->setToken( $token );
        }
        $this->getToken();

        return $this;
    }

    /**
     * @return null|string Bearer Token
     */
    public function getToken() {
        $token = $this->_twitteroauth->getBearerToken();
        Options::set( 'bearerToken', $token, true );


        return $token;
    }
}