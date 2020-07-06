<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroupConnect extends Model
{

    const CUSTOMER_GROUP_ACTIVE = 1;
    const CUSTOMER_GROUP_DELETED = -1;

    protected $table = 'customer_group_connect';
    public $timestamps = false;
}


