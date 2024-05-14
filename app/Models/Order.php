<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'quantity',
        'shipping_method',
        'is_printify_express',
        'send_shipping_notification',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'region',
        'address1',
        'address2',
        'city',
        'zip',
        'payment_method_id',
        'amount',
        'transaction_id',
        'transaction_status'
    ];
}