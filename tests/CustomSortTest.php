<?php

namespace Arni\CustomSort\Tests;

use Arni\CustomSort\CustomSortServiceProvider;
use Arni\CustomSort\Tests\Models\Post;
use Arni\CustomSort\Tests\Providers\CustomSortTestServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase;

class CustomSortTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

//        $this->loadLaravelMigrations(['--database' => 'testing']);
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->withFactories(__DIR__.'/factories');
        // and other test setup steps you need to perform
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    protected function getPackageProviders($app)
    {
        return [
            CustomSortServiceProvider::class,
            CustomSortTestServiceProvider::class
        ];
    }

    /** @test */
    public function it_sets_custom_sort_order_on_a_model()
    {
        $posts = factory(Post::class, 5)->create();

        $response = $this->getJson('/posts');
        // default order should be - 1,2,3,4,5

        $data = $response->json();
        $this->assertEquals($data[0]['id'], 1);
        $this->assertEquals($data[1]['id'], 2);
        $this->assertEquals($data[2]['id'], 3);
        $this->assertEquals($data[3]['id'], 4);
        $this->assertEquals($data[4]['id'], 5);

        // now set manual order - 2,4,1,5,3
        $response = $this->putJson('/posts/updateCustomSort', [
            'custom_sort' => [
                [
                    'id' => 2,
                    'priority' => 5
                ],
                [
                    'id' => 4,
                    'priority' => 4
                ],
                [
                    'id' => 1,
                    'priority' => 3
                ],
                [
                    'id' => 5,
                    'priority' => 2
                ],
                [
                    'id' => 3,
                    'priority' => 1
                ],
            ]
        ]);

        $response->assertStatus(200);

        $response = $this->getJson('/posts?orderby=custom_sort');
        // order - 2,4,1,5,3
        $data = $response->json();
        $this->assertEquals($data[0]['id'], 2);
        $this->assertEquals($data[1]['id'], 4);
        $this->assertEquals($data[2]['id'], 1);
        $this->assertEquals($data[3]['id'], 5);
        $this->assertEquals($data[4]['id'], 3);

        // new posts gets appended to custom sort set.
        factory(Post::class)->create();
        $response = $this->getJson('/posts?orderby=custom_sort');
        // order - 2,4,1,5,3,6
        $data = $response->json();
        $this->assertEquals($data[0]['id'], 2);
        $this->assertEquals($data[1]['id'], 4);
        $this->assertEquals($data[2]['id'], 1);
        $this->assertEquals($data[3]['id'], 5);
        $this->assertEquals($data[4]['id'], 3);
        $this->assertEquals($data[5]['id'], 6);
    }
}
