<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $table      = 'sales';
    protected $primaryKey = 'sales_id';
    public $timestamps    = false;
    protected $guarded = [];
}
