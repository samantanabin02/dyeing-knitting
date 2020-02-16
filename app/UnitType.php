<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitType extends Model
{
    protected $table      = "unit_type";
    protected $primaryKey = 'unit_type_id';
    public $timestamps = false;
}
