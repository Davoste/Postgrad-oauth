<?php

namespace App\Policies;

use App\Models\Thesis;
use App\Models\User;

class ThesisPolicy
{
    /** Who can update / transition a thesis stage. */
    public function update(User $user, Thesis $thesis): bool
    {
        if ($user->hasRole('admin') || $user->hasRole('hod')) return true;

        return $user->hasRole('supervisor')
            && $user->supervisor
            && $thesis->supervisor_id === $user->supervisor->id;
    }

    /** Who can review / submit feedback on a thesis submission. */
    public function review(User $user, Thesis $thesis): bool
    {
        if ($user->hasRole('admin')) return true;

        return $user->hasRole('supervisor')
            && $user->supervisor
            && $thesis->supervisor_id === $user->supervisor->id;
    }

    /** Who can allocate a supervisor to a thesis. */
    public function allocateSupervisor(User $user, Thesis $thesis): bool
    {
        return $user->hasRole('admin');
    }

    /** Who can submit a progress report for a thesis. */
    public function submitProgressReport(User $user, Thesis $thesis): bool
    {
        if ($user->hasRole('admin')) return true;

        return $user->hasRole('supervisor')
            && $user->supervisor
            && $thesis->supervisor_id === $user->supervisor->id;
    }
}
