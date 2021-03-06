<?php

namespace ScoutEngines\Elasticsearch;

use Laravel\Scout\Searchable as ScoutSearchable;

trait Searchable
{
	use ScoutSearchable;

	public static function searchRaw($query)
	{
		$model = new static();

		return $model->searchableUsing()
			->searchRaw($model, $query);
	}

	public static function savePhrase($params)
	{
		$model = new static();

		return $model
			->searchableUsing()
			->savePhrase($model, $params);
	}
}
