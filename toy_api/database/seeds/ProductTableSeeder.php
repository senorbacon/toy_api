<?php

use Illuminate\Database\Seeder;
use \Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('products')->delete();

        Product::create(array('name' => 'spam', 'price' => 4.65, 'in_stock' => true));
        Product::create(array('name' => 'spam lite', 'price' => 4.95, 'in_stock' => true));
        Product::create(array('name' => 'spam reduced sodium', 'price' => 4.95, 'in_stock' => true));

        Model::reguard();
    }
}
