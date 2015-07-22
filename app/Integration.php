<?php

namespace App;

use App\Credential;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model{

    public function credentials()
    {
        return $this->hasMany(Credential::class);
    }

}