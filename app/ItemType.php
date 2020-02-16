<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    protected $table      = "item_type";
    protected $primaryKey = 'item_type_id';
    public $timestamps = false;
}


