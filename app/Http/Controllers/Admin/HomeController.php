<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('home_access'), 403, '403 Forbidden');

        return view('admin.home');
    }

    public function destroyGallery($name)
    {
        if (request()->ajax() && $name) {

            $media = Media::whereRaw('md5(id) = "' . $name . '"')->first();
            $media->delete();

            return response()->json([
                'success' => "Файл {$media->file_name} успешно удалено."
            ]);
        }

        return abort(404);
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('attach')) {
            $file_dir = 'tmp/' . uniqid();

            foreach ($request->file('attach') as $item) {
                $file_name = pathinfo($item->getClientOriginalName(), PATHINFO_FILENAME);
                $file_name = ucfirst(\Str::slug($file_name)) . '.' . $item->getClientOriginalExtension();

                $item->storeAs($file_dir, $file_name);

                return "$file_dir/$file_name";
            }
        }

        return '';
    }

    public function uploadEditor(Request $request)
    {
        if ($request->hasFile('image')) {
            $file_dir = 'upload_editor/' . date('Y-m-d');

            $file = $request->file('image');
            $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
            $file_path = \Storage::path("$file_dir/$file_name");

            $file->storeAs($file_dir, $file_name);

            // Resize image
            $image = \Image::make($file_path);

            if ($image->height() > $image->width()) {
                $width = null; $height = 1000;
            } else {
                $width = 1000; $height = null;
            }

            if ($image->width() > 1000 || $image->height() > 1000) {
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $image->save($file_path);
            // end

            return [
                'success' => 'Добавлено!',
                'url'     => "/storage/$file_dir/$file_name"
            ];
        }

        return ['error' => 'Ошибка'];
    }

    public function destroyEditor(Request $request)
    {
        $file_path = str_replace(url('storage'), '', $request->input('src'));

        if (file_exists(\Storage::path($file_path))) {
            \Storage::delete($file_path);
            return ['success' => 'Удалено!'];
        }

        return ['error' => 'Ошибка'];
    }
}
