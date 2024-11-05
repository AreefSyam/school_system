<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevelModel extends Model
{
    use HasFactory;

    // Link to the grade_level table in the database
    protected $table = 'grade_level';

    // Define the fields that are mass assignable
    protected $fillable = [
        'grade_name',
        'grade_order',
    ];

    // Define the many-to-many relationship with SubjectModel
    public function subjects()
    {
        return $this->belongsToMany(SubjectModel::class, 'subject_grade', 'grade_level_id', 'subject_id');
    }
}
