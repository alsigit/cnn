<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class alfabet extends Model
{
  protected $fillable = ['no','biner','huruf','no_gambar','train'];
  // public $timestamps = false;
  protected $table = "talfabet";
}
