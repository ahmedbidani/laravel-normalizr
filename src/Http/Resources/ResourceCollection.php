<?php

namespace Eyf\Normalizr\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection as BaseCollection;

class ResourceCollection extends BaseCollection
{
  use NormalizesResource;

  protected function getItemName()
  {
    if (!isset($this->itemName)) {
      $this->itemName = $this->classToKeyName($this->collects());
    }

    return $this->itemName;
  }
}
