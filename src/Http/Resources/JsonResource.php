<?php

namespace Eyf\Normalizr\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;

class JsonResource extends BaseResource
{
  use NormalizesResource;

  public static function collection($resource)
  {
    return new AnonymousResourceCollection($resource, static::class);
  }

  public function getEntityKey()
  {
    if (!isset(static::$entityKey)) {
      static::$entityKey = $this->classToKeyName(get_class($this));
    }

    return static::$entityKey;
  }
}
