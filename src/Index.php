<?php

namespace Algolia\AlgoliaSearch;

use Algolia\AlgoliaSearch\Contracts\IndexInterface;
use Algolia\AlgoliaSearch\Internals\ApiWrapper;

final class Index implements IndexInterface
{
    private $indexName;
    protected $urlIndexName;

    /**
     * @var ApiWrapper
     */
    private $api;

    public function __construct($indexName, ApiWrapper $apiWrapper)
    {
        $this->indexName = $indexName;
        $this->urlIndexName = urlencode($indexName);
        $this->api = $apiWrapper;
    }

    public function addObjects($objects, $requestOptions = [])
    {
        $requestOptions['requests'] = $this->buildBatch('addObject', $objects);

        return $this->api->write(
            'POST',
            '/1/indexes/'.$this->urlIndexName.'/batch',
            $requestOptions
        );
    }

    private function buildBatch($action, $objects)
    {
        $operations = [];
        foreach ($objects as $obj) {
            $operations[] = [
                'action' => $action,
                'body' => $obj
            ];
        }

        return $operations;
    }
}
