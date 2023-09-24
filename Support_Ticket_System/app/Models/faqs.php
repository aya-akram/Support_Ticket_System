<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class faqs extends Model
{
    use HasFactory;
    public function departments(){
        return $this->belongsTo(departments::class, 'department_id');
    }
}
