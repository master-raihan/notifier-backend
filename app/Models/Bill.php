<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = 'bills';
    protected $fillable = [
        'currency', 'amount', 'payee', 'note', 'due_date', 'repeat', 'repeat_unit','status', 'category_id', 'user_id', 'notification', 'transaction_type'
    ];
}
