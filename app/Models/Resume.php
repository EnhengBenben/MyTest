<?php

namespace App\Models;




class Resume extends BaseModel
{
    //
    protected $table = 'resume';

    public function application(){
        return $this->belongsTo(Application::class);
}
}
