<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tickets extends Model
{
    use HasFactory;
    public function department()
    {
        return $this->belongsTo(departments::class, 'department_id'); // Assuming 'department_id' is the foreign key column
    }
    public function submittedBy(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
