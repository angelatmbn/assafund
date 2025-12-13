<?php

namespace App\Policies;

use App\Models\User;
use App\Models\komponenGaji;
use Illuminate\Auth\Access\Response;

class KomponenGajiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, komponenGaji $komponenGaji): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, komponenGaji $komponenGaji): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, komponenGaji $komponenGaji): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, komponenGaji $komponenGaji): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, komponenGaji $komponenGaji): bool
    {
        return true;
    }
}
