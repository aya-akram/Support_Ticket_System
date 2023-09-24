<?php

namespace App\Policies;

use App\Models\User;
use HandlesAuthorization;
class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function viewStaffOrClientContent(User $user)
    {
        // Check if the user has the 'staff' or 'client' role
        return $user->hasAnyRole(['staff', 'client']);
    }
}
