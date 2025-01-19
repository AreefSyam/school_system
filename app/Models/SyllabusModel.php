<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusModel extends Model
{
    use HasFactory;
    protected $table = 'syllabus';

    protected $fillable = ['syllabus_name'];

    public function subjects()
    {
        return $this->hasMany(SubjectModel::class, 'syllabus_id');
    }

}
