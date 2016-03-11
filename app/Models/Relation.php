<?php

namespace App\Models;




class Relation extends BaseModel
{
    //
    protected $table = 'relation';

    public function application(){
        return $this->belongsTo(Application::class);
}
}
