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

    static public function getSubject()
    {
        $subject = SubjectModel::select('subject.*')
                ->join('users', 'users.id', 'subject.who_created')
                ->where('subject.is_delete', '=', 1)
                ->where('subject.status', '=', 1)
                ->orderBy('subject.name', 'asc')
                ->get();

        return $subject;
    }
}
