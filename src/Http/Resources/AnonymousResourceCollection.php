<?php

namespace Eyf\Normalizr\Http\Resources;

class AnonymousResourceCollection extends ResourceCollection
{
  /**
   * The name of the resource being collected.
   *
   * @var string
   */
  public $collects;

  public static $entityKey;

  /**
   * Create a new anonymous resource collection.
   *
   * @param  mixed  $resource
   * @param  string  $collects
   * @return void
   */
  public function __construct($resource, $collects)
  {
    $this->collects = $collects;
    
    static::$entityKey = $collects::$entityKey;

    parent::__construct($resource);
  }
}
