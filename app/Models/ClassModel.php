<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = "class";

    protected $fillable = ['id', 'name', 'status', 'is_delete', 'who_created', 'created_at', 'who_updated', 'updated_at'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'who_created', 'id');
    }
}
