<?php


namespace App\Tools\Twitter;

class SearchMetadata extends \stdClass {
    public $max_id;
    public $since_id;
    public $refresh_url;
    public $next_results;
    public $count;
    public $completed_in;
    public $since_id_str;
    public $query;
    public $max_id_str;

    public function __construct( $meta ) {
        $this->max_id       = $meta->max_id;
        $this->since_id     = $meta->since_id;
        $this->refresh_url  = isset( $meta->refresh_url ) ? $meta->refresh_url : null;
        $this->next_results = isset( $meta->next_results ) ? $meta->next_results : null;
        $this->count        = $meta->count;
        $this->completed_in = $meta->completed_in;
        $this->since_id_str = $meta->since_id_str;
        $this->query        = $meta->query;
        $this->max_id_str   = $meta->max_id_str;

    }
}