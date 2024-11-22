<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $academic_year_name
 * @property int $status 0: active, 1:inactive
 * @property string $start_date First day of the year
 * @property string $end_date Final day of the year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel whereAcademicYearName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AcademicYearModel whereUpdatedAt($value)
 */
	class AcademicYearModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property int $status 0: active, 1:inactive
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $grade_level_id
 * @property int $academic_year_id
 * @property-read \App\Models\AcademicYearModel $academicYear
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentModel> $students
 * @property-read int|null $students_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereAcademicYearId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereGradeLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereUpdatedAt($value)
 */
	class ClassModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $exam_type_id
 * @property int $academic_year_id
 * @property int $syllabus_id
 * @property string $exam_name
 * @property string $start_date
 * @property string $end_date
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AcademicYearModel $academicYear
 * @property-read \App\Models\ExamTypeModel $examType
 * @property-read \App\Models\SyllabusModel $syllabus
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel whereAcademicYearId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel whereExamName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel whereExamTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel whereSyllabusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamModel whereUpdatedAt($value)
 */
	class ExamModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $exam_type_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExamModel> $exams
 * @property-read int|null $exams_count
 * @method static \Illuminate\Database\Eloquent\Builder|ExamTypeModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamTypeModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamTypeModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamTypeModel whereExamTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamTypeModel whereId($value)
 */
	class ExamTypeModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $grade_name 1:Tahun 1,2:Tahun 2,3:Tahun 3, 4:Tahun 4, 5:Tahun 5, 6:Tahun 6
 * @property int|null $grade_order 1,2,3,4,5,6
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubjectModel> $subjects
 * @property-read int|null $subjects_count
 * @method static \Illuminate\Database\Eloquent\Builder|GradeLevelModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GradeLevelModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GradeLevelModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|GradeLevelModel whereGradeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GradeLevelModel whereGradeOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GradeLevelModel whereId($value)
 */
	class GradeLevelModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $student_id
 * @property int $class_id
 * @property int $subject_id
 * @property int $syllabus_id
 * @property int $exam_type_id
 * @property int $academic_year_id
 * @property int|null $mark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $created_by
 * @property-read \App\Models\AcademicYearModel $academicYear
 * @property-read \App\Models\ClassModel $class
 * @property-read \App\Models\ExamTypeModel $examType
 * @property-read \App\Models\StudentModel $student
 * @property-read \App\Models\SubjectModel $subject
 * @property-read \App\Models\SyllabusModel $syllabus
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereAcademicYearId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereExamTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereSyllabusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MarkModel whereUpdatedAt($value)
 */
	class MarkModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $student_code
 * @property string|null $full_name
 * @property string $date_of_birth
 * @property string|null $address
 * @property string $gender
 * @property string $enrollment_date
 * @property string $ic_number
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassModel> $classes
 * @property-read int|null $classes_count
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereEnrollmentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereIcNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereStudentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentModel whereUpdatedAt($value)
 */
	class StudentModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $student_id
 * @property int $class_id
 * @property int $exam_type_id
 * @property int $syllabus_id
 * @property int $academic_year_id
 * @property int|null $total_marks
 * @property string|null $percentage
 * @property string|null $total_grade
 * @property int|null $position_in_class
 * @property int|null $position_in_grade
 * @property int|null $attendance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AcademicYearModel $academicYear
 * @property-read \App\Models\ClassModel $class
 * @property-read \App\Models\ExamTypeModel $examType
 * @property-read \App\Models\StudentModel $student
 * @property-read \App\Models\SyllabusModel $syllabus
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereAcademicYearId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereAttendance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereExamTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel wherePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel wherePositionInClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel wherePositionInGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereSyllabusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereTotalGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereTotalMarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentSummaryModel whereUpdatedAt($value)
 */
	class StudentSummaryModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $subject_name
 * @property int|null $syllabus_id
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $academic_year_id
 * @property-read \App\Models\AcademicYearModel $academicYear
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GradeLevelModel> $gradeLevels
 * @property-read int|null $grade_levels_count
 * @property-read \App\Models\SyllabusModel|null $syllabus
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectModel whereAcademicYearId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectModel whereSubjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectModel whereSyllabusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubjectModel whereUpdatedAt($value)
 */
	class SubjectModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $syllabus_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubjectModel> $subjects
 * @property-read int|null $subjects_count
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel whereSyllabusName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SyllabusModel whereUpdatedAt($value)
 */
	class SyllabusModel extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $role
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

