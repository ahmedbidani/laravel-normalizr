<?php

namespace Eyf\Normalizr\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\MissingValue;

trait NormalizesResource
{
  public $preserveKeys = true;

  protected $resourceName;
  protected $schema = [];

  abstract protected function getResourceName();

  public function getSchema()
  {
    return $this->schema;
  }

  public function resolve($request = null)
  {
    $data = parent::resolve($request);
    $entities = [];

    $resourceName = $this->getResourceName();

    if ($this instanceof ResourceCollection) {
      $result = $this->walkItems($data, $resourceName, $entities, $request);
    } else {
      $result = $this->walkItem($data, $resourceName, $entities, $request);
    }

    return compact('entities', 'result');
  }

  protected function walkItem(
    array $item,
    string $itemName,
    array &$entities,
    $request
  ) {
    foreach ($item as $key => $value) {
      $isResource = $value instanceof JsonResource;
      $isMissingValue = $isResource && $value->resource instanceof MissingValue;

      if (!$isResource || $isMissingValue || !in_array($key, $this->schema)) {
        continue;
      }

      $relationName = $this->relationToKeyName($key);

      if (!isset($entities[$relationName])) {
        $entities[$relationName] = [];
      }

      if ($value instanceof ResourceCollection) {
        // Normalize collection relationship
        $item[$key] = $this->walkItems(
          $value,
          $relationName,
          $entities,
          $request
        );
      } else {
        // Normalize resource relationship
        $item[$key] = $this->walkItem(
          is_array($value) ? $value : $value->toArray($request),
          $relationName,
          $entities,
          $request
        );
      }
    }

    if (!isset($entities[$itemName])) {
      $entities[$itemName] = [];
    }

    $entities[$itemName][$item['id']] = $item;

    return $item['id'];
  }

  protected function walkItems(
    $items,
    string $itemName,
    array &$entities,
    $request
  ) {
    $result = [];

    foreach ($items as $item) {
      $id = $this->walkItem(
        is_array($item) ? $item : $item->toArray($request),
        $itemName,
        $entities,
        $request
      );

      array_push($result, $id);
    }

    return $result;
  }

  protected function classToKeyName($class)
  {
    $key = explode('\\', $class);
    $key = array_pop($key);
    $key = Str::plural($key);

    return Str::snake($key);
  }

  protected function relationToKeyName($key)
  {
    return Str::plural($key);
  }
}
