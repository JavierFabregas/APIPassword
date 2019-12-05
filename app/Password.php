<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{    
	protected $table = 'passwords';
    protected $fillable = ['category_id','title','password'];

    public function register($id_category, $title, $passwordReceived)
    {

        $password = new Password;
        $password->category_id = $id_category;
        $password->title = $title;
        $password->password = $passwordReceived;
        $password->save();

    }
}
