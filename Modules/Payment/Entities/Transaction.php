<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Entities\Concerns\UsesUuid;

class Transaction extends Model
{

    use UsesUuid;

    protected $fillable = [
        'user_id','transaction_id','response_message'
    ];

}
