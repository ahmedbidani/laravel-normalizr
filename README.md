# laravel-normalizr
Normalizr Laravel Eloquent API Resources

## Install

```
composer require eyf/laravel-normalizr
```

## Usage

```php
<?php
namespace App\Http\Resources;

use Eyf\Normalizr\Http\Resources\JsonResource;

class User extends JsonResource
{
  return [
    'id' => $this->id,
    'name' => $this->name,
    // ...

    'posts' => Post::collection($this->whenLoaded('posts')),
  ];
}
```
