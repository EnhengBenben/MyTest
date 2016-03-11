<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
class Application extends BaseModel implements Authenticatable
{
    //
    protected $table = 'application';
    public $timestamps = false;
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        return;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return null;
    }

    public function education(){
        return $this->hasMany(Education::class);
}
    public function resume(){
        return $this->hasMany(Resume::class);
    }

    public function paper(){
        return $this->hasMany(Paper::class);
    }

    public function course(){
        return $this->belongsToMany(Course::class,'application_course_result')
            ->withPivot('rejected_at','passed_at','confirm','confirmed_at','transfer_course_id','postpone_course_id','submitted_at');
    }

    public function region(){
        return $this->belongsTo(Region::class);
    }

    public function org_rank(){
        return $this->belongsTo(Org_rank::class);
    }

    public function admin_duty(){
        return $this->belongsTo(Admin_duty::class);
    }

    public function tech_duty(){
        return $this->belongsTo(Tech_duty::class);
    }

    public function degree(){
        return $this->belongsTo(Degree::class);
    }
}
