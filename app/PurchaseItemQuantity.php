<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseItemQuantity extends Model
{
    protected $table      = 'purchase_item_quantity';
    protected $primaryKey = 'id';
    public $timestamps    = false;
    protected $guarded = [];

}
