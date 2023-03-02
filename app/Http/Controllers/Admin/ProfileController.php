<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\AppModel;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(User $user)
    {
        if ($user->load('media')->getMedia('avatar')->count()) {
            $user->avatar = $user->getFirstMedia('avatar')->getUrl('thumb');
        }

        return view('admin.profile.edit', compact('user'));
    }

    public function update(ProfileRequest $request, User $user)
    {
        $user->name = $request->input('name');
        $user->update();

        AppModel::saveDeleteImage($user, $request, ['avatar']);

        return redirect()
            ->route('admin.profile.edit', auth()->id())
            ->with('status', 'Ваш профиль успешно обновлен.');
    }
}
