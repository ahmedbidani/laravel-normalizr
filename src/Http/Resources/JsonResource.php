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

  protected function getResourceName()
  {
    if (!isset($this->resourceName)) {
      $this->resourceName = $this->classToKeyName(get_class($this));
    }

    return $this->resourceName;
  }
}
