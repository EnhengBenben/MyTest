<?php

namespace App\Models;




class Degree extends BaseModel
{
    //
    protected $table = 'degree';

    public function application(){
        return $this->hasMany(Application::class);
}
}
