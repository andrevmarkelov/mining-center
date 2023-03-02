<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Gate;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('role_access'), 403, '403 Forbidden');

        return view('admin.roles.index', [
            'roles' => Role::with('permissions')->get()
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), 403, '403 Forbidden');

        return view('admin.roles.create', [
            'permissions' => Permission::all()->pluck('title', 'id')
        ]);
    }

    public function store(RoleRequest $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'Роль успешно добавлена.');
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_edit'), 403, '403 Forbidden');

        $permissions = Permission::all()->pluck('title', 'id');

        $role->load('permissions');

        return view('admin.roles.edit', compact('permissions', 'role'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()
            ->route('admin.roles.index')
            ->with('status', 'Роль успешно обновлена.');
    }

    public function destroy(Role $role)
    {
        abort_if(Gate::denies('role_delete'), 403, '403 Forbidden');

        $role->delete();

        return back()->with('status', 'Роль успешно удалена.');
    }
}
