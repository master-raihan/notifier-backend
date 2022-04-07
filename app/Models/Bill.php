<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = 'bills';
    protected $fillable = [
        'amount', 'payee', 'note', 'due_date', 'repeat', 'status', 'category_id', 'user_id'
    ];
}
