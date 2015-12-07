<?php

namespace App\Tools\Twitter;


class TwitterSearchResult extends \stdClass {
    /** @var \stdClass[] Tweets list  */
    public $statuses =[];
    /** @var SearchMetadata  Metaobject */
    public $search_metadata;
    /** @var CustomMeta customized meta */
    public $meta;

    public function __construct($res) {
        $this->statuses = $res->statuses;
        $this->search_metadata = new SearchMetadata($res->search_metadata);
        $this->meta = new CustomMeta();
    }
}