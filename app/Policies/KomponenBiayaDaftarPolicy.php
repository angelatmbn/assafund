<?php

namespace App\Policies;

use App\Models\User;
use App\Models\komponenBiayaDaftar;
use Illuminate\Auth\Access\Response;

class KomponenBiayaDaftarPolicy
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
    public function view(User $user, komponenBiayaDaftar $komponenBiayaDaftar): bool
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
    public function update(User $user, komponenBiayaDaftar $komponenBiayaDaftar): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, komponenBiayaDaftar $komponenBiayaDaftar): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, komponenBiayaDaftar $komponenBiayaDaftar): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, komponenBiayaDaftar $komponenBiayaDaftar): bool
    {
        return true;
    }
}
