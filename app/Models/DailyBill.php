<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyBill extends Model
{
    protected $table = 'daily_bills';
    protected $fillable = [
        'time_before', 'status', 'bill_id'
    ];
}
