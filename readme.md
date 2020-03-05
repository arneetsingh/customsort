# CustomSort (Laravel Package)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

CustomSort is a laravel package to give ability to manually sort items of any Eloquent Model.

<img src="https://i.imgur.com/9UMecVz.gif" />

## Installation

Via Composer

``` bash
$ composer require arneetsingh/customsort
```

## Usage

#### Migrate
```php
php artisan migrate
```

#### Model
Use the `HasCustomSortTrait` trait in your model you want to have the ability of sorting manually.
```php
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Arni\CustomSort\Traits\HasCustomSortTrait;

class Post extends Model
{
    use HasCustomSortTrait;
}

```
This gives the ability to call `orderByCustomSort()` on Post model.
#### Routes
You need two routes to get model collection and second to update manual sort order
```php
Route::get('/posts', 'PostsController@index');
Route::put('/posts/updateCustomSort', 'PostsController@updateCustomSort');
```
#### Controller
To set the custom sort order, use `HasCustomSortEndpoint` trait in your controller. This will handle the logic for `updateCustomSort` request,
and set `$model` property to denote which model to work with.
```php
namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Arni\CustomSort\Traits\HasCustomSortEndpoint;

class PostsController
{
    use HasCustomSortEndpoint;

    public $model = Post::class;
    ...
}

```
## Example
#### Setting the custom sort order

Lets say we have 5 posts in our Post model.
and we want to set them in order of ids - 2,4,1,5,3
```php
Request: PUT
Endpoint: /posts/updateCustomSort
Payload:
{
	"custom_sort":[
		{
			"id":2,
			"priority":5			
		},
		{
			"id":4,
			"priority":4			
		},
		{
			"id":1,
			"priority":3			
		},
		{
			"id":5,
			"priority":2			
		},
		{
			"id":3,
			"priority":1			
		}
	]
}
```

#### Fetch posts in manual order.

As mentioned above we have `orderByCustomSort()` on Post model.
We can use that to get posts in manually set order, or
There is also another method `orderByWithCustomSort()` that is just a wrapper to handle both cases of custom sort or conventional database column sort
```php
Request: GET
Endpoint: /posts?orderby=custom_sort

PostsController:
public function index(Request $request)
{
    $posts = Post::orderByWithCustomSort($request->orderby ?? 'id')->get();
    return $posts;
}
```
### Frontend Tips
I would suggest using [SortabelJS](https://github.com/SortableJS/Sortable) for having the ability to drag and drop to set manual order.
And here is snippet to javascript code.
```javascript
let posts = []

// SortableJS onEnd handler would look like this
onEnd: function ({ oldIndex, newIndex }){
  const movedItem = posts.splice(oldIndex, 1)[0]
  posts.splice(newIndex, 0, movedItem)
}

// prepare payload
preparePayload: function() {
  const order = posts.map((item, key) => {
    return {
      id: item.id,
      priority: posts.length - key
    }
  })
  return { custom_sort: order }
}
```
## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.


## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/arneetsingh/customsort.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/arneetsingh/customsort.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/arneetsingh/customsort/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/arneetsingh/customsort
[link-downloads]: https://packagist.org/packages/arneetsingh/customsort
[link-travis]: https://travis-ci.org/arneetsingh/customsort
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/arneetsingh
[link-contributors]: ../../contributors
