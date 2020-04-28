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

    if ($item instanceof NormalizrResource) {
      $this->schema = $item->getSchema();
    } else {
      $this->schema = [];
    }
  }

  protected function getItemName()
  {
    if (!isset($this->itemName)) {
      $this->itemName = $this->classToKeyName($this->collects());
    }

    return $this->itemName;
  }
}
