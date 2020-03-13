<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesItemQuantity extends Model
{
    protected $table      = 'sales_item_quantity';
    protected $primaryKey = 'id';
    public $timestamps    = false;
    protected $guarded = [];

}
