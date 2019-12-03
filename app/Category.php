<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	protected $table = 'categories';
    protected $fillable = ['name','user_id'];

    public function passwords()
    {
        return $this->hasMany('App\Password','category_id');
    }
}
