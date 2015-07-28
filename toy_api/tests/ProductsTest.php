<?php

use Illuminate\Support\Facades\Artisan;

class ProductsTest extends TestCase
{
    public function setUp() {
        parent::setUp();

        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }

    public function testGetProducts()
    {
        $this->get('api/v1/products')
            ->seeJson(['name' => 'spam'])
            ->seeJson(['name' => 'spam lite'])
            ->seeJson(['name' => 'spam reduced sodium']);
    }

    public function testGetProduct()
    {
        $this->get('api/v1/products/1')
            ->seeJson(['name' => 'spam']);
    }

    public function testCreateProduct()
    {
        $this->post('api/v1/products', ['name' => 'bacon', 'price' => 6.19, 'in_stock' => 1])
            ->seeJson(['name' => 'bacon'])
            ->seeInDatabase('products', ['name' => 'bacon', 'price' => 6.19]);
    }

    public function testUpdateProduct()
    {
        $this->put('api/v1/products/1', ['price' => 5.55, 'in_stock' => 0])
            ->seeJson(['name' => 'spam', 'price' => "5.55"])
            ->seeInDatabase('products', ['name' => 'spam', 'price' => 5.55]);
    }

    public function testDestroyProduct()
    {
        $this->delete('api/v1/products/1');

        $response = $this->call('GET', 'api/v1/products/1');

        $this->assertEquals(404, $response->getStatusCode());
    }
}
