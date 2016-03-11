<?php

namespace App\Models;




class Education extends BaseModel
{
    //
    protected $table = 'education';

    public function application(){
        return $this->belongsTo(Application::class);
}
}
