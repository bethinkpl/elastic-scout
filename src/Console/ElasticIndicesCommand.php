<?php

namespace ScoutEngines\Elasticsearch\Console;

use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

/**
 * @author https://github.com/thomasjsn
 * @link https://github.com/thomasjsn/laravel-scout-elastic/blob/master/src/Console/ElasticIndicesCommand.php
 */
class ElasticIndicesCommand extends Command
{
    protected $signature = 'scout:indices';

    protected $description = 'Show Elasticsearch indices (cat command)';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $host = config('elasticsearch.hosts');

        $client = ClientBuilder::create()->setHosts($host)->build();

        $indices = $client->cat()->indices();

        if(count($indices) > 0) {
            $headers = array_keys(current($indices));
            $this->table($headers, $indices);
        } else {
            $this->warn('No indices found.');
        }
    }
}
