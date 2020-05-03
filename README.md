# laravel-normalizr
Normalizr Laravel Eloquent API Resources

## Install

```
composer require eyf/laravel-normalizr
```

## Usage

```php
use Eyf\Normalizr\Http\Resources\JsonResource;

class User extends JsonResource
{
  public function toArray($request)
  {  
    return [
      'id' => $this->id,
      'name' => $this->name,
      // ...

      'posts' => Post::collection($this->whenLoaded('posts')),
    ];
  }
}
```

### Controller

Assuming [route model binding](https://laravel.com/docs/7.x/routing#route-model-binding).

```php
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
  public function find(Request $request, User $user)
  {
    $user->loadMissing('posts');
    
    return new UserResource($user);
  }
}
```

### Response

```json
{
  "data": {
    "entities": {
      "users": {
        "1": {
          "id": 1,
          "name": "John",
          "posts": [2, 3]
        }
      },
      "posts": {
        "2": {
          "id": 2,
          "title": "Post 2"
        },
        "3": {
          "id": 3,
          "title": "Post 3"
        }
      }
    },
    "result": 1
  }
}
```
