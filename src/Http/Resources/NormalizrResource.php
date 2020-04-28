<?php

namespace Eyf\Normalizr\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NormalizrResource extends JsonResource
{
  use NormalizesResource;

  public static function collection($resource)
  {
    return new AnonymousNormalizrCollection($resource, static::class);
  }

  protected function getItemName()
  {
    if (!isset($this->itemName)) {
      $this->itemName = $this->classToKeyName(get_class($this));
    }

    return $this->itemName;
  }
}
