<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    //
    public function shops(){
        return $this->belongsToMany('App\Models\Shop');
    }
}
