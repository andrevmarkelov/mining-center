<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Service;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('service_access');
    }

    public function create(User $user)
    {
        return $user->can('service_create');
    }

    public function update(User $user, Service $service)
    {
        return $user->can('service_edit');
    }

    public function delete(User $user, Service $service)
    {
        return $user->can('service_delete');
    }
}
