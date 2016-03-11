<?php

namespace App\Models;




class Director extends BaseModel
{
    //
    protected $table = 'director';

    public function application(){
        return $this->belongsTo(Application::class);
}
}
