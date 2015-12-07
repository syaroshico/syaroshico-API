<?php

namespace App\Tools\Twitter;


class TwitterAppCredentials {

    public $consumer_key;
    public $consumer_secret;

    public function __construct( $consumer_key = null, $consumer_secret = null ) {
        $this->consumer_key    = $consumer_key;
        $this->consumer_secret = $consumer_secret;

    }
}