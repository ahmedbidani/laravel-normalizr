<?php

namespace Eyf\Normalizr\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection as BaseCollection;

class ResourceCollection extends BaseCollection
{
  use NormalizesResource;

  public function __construct($resource)
  {
    parent::__construct($resource);

    $itemClass = $this->collects();
    $item = new $itemClass([]);

    if ($item instanceof JsonResource) {
      $this->schema = $item->getSchema();
    } else {
      $this->schema = [];
    }
  }

  protected function getResourceName()
  {
    if (!isset($this->resourceName)) {
      $this->resourceName = $this->classToKeyName($this->collects());
    }

    return $this->resourceName;
  }
}
