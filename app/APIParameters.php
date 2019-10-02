<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class APIParameters extends Model
{
    private $baseAPIURL = 'http://localhost:8000/api/';

    public function getBaseAPIURL()
    {
        return $this->baseAPIURL;
    }
}
