<?php

namespace App\Models;




class Paper extends BaseModel
{
    //
    protected $table = 'paper';

    public function application(){
        return $this->belongsTo(Application::class);
}
}
