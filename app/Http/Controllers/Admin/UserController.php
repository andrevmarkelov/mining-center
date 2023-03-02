<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Gate;

class UserController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), 403, '403 Forbidden');

        if (request()->ajax()) {
            $users = User::with('roles', 'media')->when(!request()->input('order'), function($query) {
                $query->latest();
            });

            return datatables()->of($users)
                ->editColumn('created_at', function($data) {
                    return date_format($data->created_at, 'd.m.Y H:i');
                })
                ->addColumn('thumb', function($data) {
                    return $data->thumb;
                })
                ->addColumn('contacts', function($data) {
                    $contacts = $data->email;
                    return $contacts;
                })
                ->addColumn('roles', function($data) {
                    return '<span class="badge badge-info">' . implode('</span> <span class="badge badge-info">', $data->roles->pluck('name')->toArray()) . '</span>';
                })
                ->addColumn('action', function($data) {
                    return view('admin.components.action', [
                        'route' => 'users',
                        'can' => 'user',
                        'id' => $data->id,
                    ]);
                })
                ->rawColumns(['name', 'contacts', 'roles'])
                ->toJson();
        }

        return view('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), 403, '403 Forbidden');

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::get(),
        ]);
    }

    public function update(UserRequest $request, User $user)
    {
        $user->roles()->sync($request->roles);

        if ($password = $request->input('password')) {
            $user->password = \Hash::make($password);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->update();

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Пользователь успешно обновлен.');
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), 403, '403 Forbidden');

        $user->delete();

        return back()->with('status', 'Пользователь успешно удален.');
    }
}
