<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departments extends Model
{
    use HasFactory;
    protected $fillable = ['name',];
    public function tickets()
    {
        return $this->hasMany(ticket::class, 'department_id'); // Assuming 'department_id' is the foreign key in the tickets table
    }
}
