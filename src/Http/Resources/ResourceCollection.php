<?php

namespace Eyf\Normalizr\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection as BaseCollection;

class ResourceCollection extends BaseCollection
{
  use NormalizesResource;

  public function getEntityKey()
  {
    if (!isset(static::$entityKey)) {
      static::$entityKey = $this->classToKeyName($this->collects());
    }

    return static::$entityKey;
  }
}
