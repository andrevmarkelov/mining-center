<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use Gate;

class PermissionController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('permission_access'), 403, '403 Forbidden');

        return view('admin.permissions.index', [
            'permissions' => Permission::orderByDesc('id')->get()
        ]);
    }

    public function create()
    {
        abort_if(Gate::denies('permission_create'), 403, '403 Forbidden');

        return view('admin.permissions.create');
    }

    public function store(PermissionRequest $request)
    {
        $request['title'] = $request->title . $request->action;
        Permission::create($request->all());

        return redirect()
            ->route('admin.permissions.index')
            ->with('status', 'Правило успешно добавлено.');
    }

    public function edit(Permission $permission)
    {
        abort_if(Gate::denies('permission_edit'), 403, '403 Forbidden');

        $data = explode('_', strrev($permission->title), 2);

        $permission->title = strrev($data[1]);
        $permission->action = '_' . strrev($data[0]);

        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $request['title'] = $request->title . $request->action;
        $permission->update($request->all());

        return redirect()
            ->route('admin.permissions.index')
            ->with('status', 'Правило успешно обновлено.');
    }

    public function destroy(Permission $permission)
    {
        abort_if(Gate::denies('permission_delete'), 403, '403 Forbidden');

        $permission->delete();

        return back()->with('status', 'Правило успешно удалено.');
    }
}
