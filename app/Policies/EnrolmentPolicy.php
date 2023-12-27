<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Enrolment;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnrolmentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Enrolment $enrolment)
    {
        return $user->id === $enrolment->user_id;
    }

    public function update(User $user, Enrolment $enrolment)
    {
        return $user->id === $enrolment->user_id;
    }

    public function delete(User $user, Enrolment $enrolment)
    {
        return $user->id === $enrolment->user_id;
    }
}
