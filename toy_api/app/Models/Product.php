<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $table = "products";

    public $fillable = [
        "name",
        "price",
        "in_stock",
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name" => "string",
        "price" => "float",
        "in_stock" => "boolean"
    ];
}
