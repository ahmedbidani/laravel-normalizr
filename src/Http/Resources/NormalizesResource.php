<?php

namespace Eyf\Normalizr\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\MissingValue;

trait NormalizesResource
{
  public $preserveKeys = true;

  public static $entityKey;

  abstract public function getEntityKey();

  public function resolve($request = null)
  {
    $data = parent::resolve($request);
    $entities = [];

    $entityKey = $this->getEntityKey();

    if ($this instanceof ResourceCollection) {
      $result = $this->walkItems($data, $entityKey, $entities, $request);
    } else {
      $result = $this->walkItem($data, $entityKey, $entities, $request);
    }

    return compact('entities', 'result');
  }

  protected function walkItem(
    array $item,
    string $entityKey,
    array &$entities,
    $request
  ) {
    foreach ($item as $key => $value) {
      $isResource = $value instanceof JsonResource;

      if (!$isResource) {
        continue;
      }

      $isMissing = $value->resource instanceof MissingValue;

      if ($isMissing) {
        unset($item[$key]);
        continue;
      }

      $relationName = $value->getEntityKey();

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
        if ($value->resource) {
          $item[$key] = $this->walkItem(
            $value->toArray($request),
            $relationName,
            $entities,
            $request
          );
        } else {
          $item[$key] = null;
        }
      }
    }

    if (!isset($entities[$entityKey])) {
      $entities[$entityKey] = [];
    }

    $entities[$entityKey][$item['id']] = $item;

    return $item['id'];
  }

  protected function walkItems(
    $items,
    string $entityKey,
    array &$entities,
    $request
  ) {
    $result = [];

    foreach ($items as $item) {
      $id = $this->walkItem(
        is_array($item) ? $item : $item->toArray($request),
        $entityKey,
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
