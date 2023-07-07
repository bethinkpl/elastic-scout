<?php

namespace ScoutEngines\Elasticsearch\Console;

use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

/**
 * @author https://github.com/thomasjsn
 * @link https://github.com/thomasjsn/laravel-scout-elastic/blob/master/src/Console/ElasticMakeIndicesCommand.php
 */
class ElasticMakeIndicesCommand extends Command
{
    protected $signature = 'scout:make-indices {index?} {--force}';

    protected $description = 'Make ElasticSearch indices defined in config file, with mapping';

    public function handle(): void {
        $client = ClientBuilder::create()
            ->setHosts(config('scout.elasticsearch.hosts'))
            ->build();

        $indices = !is_null($this->argument('index')) ?
            [$this->argument('index')] : array_keys(config('scout.indices'));

        foreach ($indices as $index) {

            $indexConfig = config("scout.indices.{$index}");

            if (is_null($indexConfig)) {
                $this->error("Config for index \"{$index}\" not found, skipping...");
                continue;
            }

            // Delete index if it already exists
            if ($client->indices()->exists(['index' => $index])) {
                if ($this->option('force') || $this->confirm("Index \"{$index}\" exists, delete and recreate?")) {
                    $this->warn("Index \"{$index}\" exists, deleting!");
                    $client->indices()->delete(['index' => $index]);
                } else {
                    $this->line("Skipping index: \"{$index}\"");
                    continue;
                }
            }

            // Create index with settings from config file
            $this->info("Creating index: {$index}");
            $client->indices()->create([
                'index' => $index,
                'body' => [
                    "settings" => $indexConfig['settings']
                ]
            ]);

            if (!isset($indexConfig['mappings'])) {
                continue;
            }

            foreach ($indexConfig['mappings'] as $type => $mapping) {

                // Create mapping for type, from config file
                $this->info("- Creating mapping for: {$type}");
                $client->indices()->putMapping([
                    'index' => $index,
                    'type' => $type,
                    'body' => [
                        'properties' => $mapping
                    ]
                ]);
            }

        }
    }
}
