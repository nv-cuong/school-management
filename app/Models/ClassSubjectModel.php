<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSubjectModel extends Model
{
    use HasFactory;

    protected $table = "class_subject";
    
    protected $fillable = ['id', 'class_id', 'subject_id', 'status', 'is_delete',
                'who_created', 'created_at', 'who_updated', 'updated_at'];

    public $timestamps = true;
}
