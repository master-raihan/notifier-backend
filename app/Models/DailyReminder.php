<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReminder extends Model
{
    protected $table = 'daily_reminders';
    protected $fillable = [
        'time_before', 'status', 'bill_id', 'user_id', 'data'
    ];
}
