<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectModel extends Model
{
    use HasFactory;

    protected $table = "subject";

    protected $fillable = ['id', 'name', 'status', 'type', 'is_delete', 'who_created', 'created_at', 'who_updated', 'updated_at'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'who_created', 'id');
    }
}
