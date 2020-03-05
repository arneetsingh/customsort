<?php
namespace Arni\CustomSort\Tests\Providers;

use Arni\CustomSort\Tests\Models\Post;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class CustomSortTestServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

//        Relation::morphMap([
//            'post' => Post::class,
//        ]);
    }

    public function register()
    {

    }
}
