<?php

namespace App\Models;




class Admin_duty extends BaseModel
{
    //
    protected $table = 'admin_duty';

    public function application(){
        return $this->hasMany(Application::class);
}
}
