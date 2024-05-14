<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;


class StripeCustomers extends Model
{
    use Billable;
    protected $fillable = [
        'id',
        'email',
        'customer_id',
        'status'
    ];
}