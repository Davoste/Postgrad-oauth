<?php

namespace App\Policies;

use App\Models\SupervisionMeeting;
use App\Models\User;

class SupervisionMeetingPolicy
{
    /** Student can only sign off a meeting assigned to them. */
    public function sign(User $user, SupervisionMeeting $meeting): bool
    {
        return $user->hasRole('student')
            && $user->student
            && $meeting->student_id === $user->student->id
            && !$meeting->signed_student;
    }
}
