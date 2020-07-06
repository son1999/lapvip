<?php
namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class SocialFacebookAccount extends Model
{
    protected $table = 'social_facebook_accounts';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(Customer::class);
    }
}