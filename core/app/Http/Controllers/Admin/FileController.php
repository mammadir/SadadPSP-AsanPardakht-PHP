<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $files = File::where('status', '=', File::$status['active'])->orderBy('id', 'desc')->paginate(15);

        return view('fp::admin.files.index')
            ->with('activeMenu', 'files')
            ->with('files', $files);
    }

    public function showAdd()
    {
        return view('fp::admin.files.add')
            ->with('activeMenu', 'files');
    }

    public function add(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'file' => 'required',
            'expire_day' => 'required|numeric',
            'amount' => 'required|numeric|greater_than_rial:1000',
            'image' => 'image',
        ];
        $this->validate($request, $rules);

        try {
            return DB::transaction(function () use ($request) {
                $fields = [];
                foreach ($request->fields as $key => $field) {
                    if ($field) {
                        array_push($fields, [
                            'name' => 'field_' . $key,
                            'label' => $field,
                            'required' => array_search('required_' . $key, $request->required_fields ? $request->required_fields : []) !== false ? true : false,
                        ]);
                    }
                }
                $file = File::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'amount' => $request->amount,
                    'pay_limit' => $request->pay_limit,
                    'expire_day' => $request->expire_day,
                    'fields' => $fields,
                    'form_size' => $request->form_size ? $request->form_size : 4
                ]);

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $path = get_date_path();
                    $imageName = $path . '/' . uniqid() . $file->id . '.' . $image->getClientOriginalExtension();
                    Storage::disk('files-image')->put($imageName, file_get_contents($image));
                    $file->update(['image' => 'storage/files-image/' . $imageName]);
                }

                if ($request->hasFile('file')) {
                    $f = $request->file('file');
                    $path = get_date_path();
                    $fileName = $path . '/' . uniqid() . $file->id . '.' . $f->getClientOriginalExtension();
                    Storage::disk('files')->put($fileName, file_get_contents($f));
                    $file->update(['file' => 'app/files/' . $fileName]);
                }

                return redirect()->back()
                    ->with('alert', 'success')
                    ->with('message', lang('lang.added'));
            });
        } catch (\Exception $e) {
            return handle_exception($e);
        }
    }

    public function showEdit(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $rules = [
            'id' => 'required|exists:fp_files,id',
        ];
        $this->validate($request, $rules);

        $file = File::find($id);

        return view('fp::admin.files.edit')
            ->with('activeMenu', 'files')
            ->with('file', $file);
    }

    public function edit(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $rules = [
            'id' => 'required|exists:fp_files,id',
            'amount' => 'required|numeric|greater_than_rial:1000',
            'title' => 'required',
            'expire_day' => 'required|numeric',
            'image' => 'image',
        ];
        $this->validate($request, $rules);

        try {
            return DB::transaction(function () use ($request, $id) {
                $file = File::find($id);
                $fields = [];
                if ($request->fields) {
                    foreach ($request->fields as $key => $field) {
                        array_push($fields, [
                            'name' => 'field_' . $key,
                            'label' => $field,
                            'required' => array_search('required_' . $key, $request->required_fields) !== false ? true : false,
                        ]);
                    }
                }

                $file->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'amount' => $request->amount,
                    'pay_limit' => $request->pay_limit,
                    'expire_day' => $request->expire_day,
                    'fields' => $fields,
                    'form_size' => $request->form_size ? $request->form_size : 4
                ]);

                if ($request->hasFile('image')) {
                    if ($file->image && file_exists(base_path('../' . $file->image))) {
                        unlink(base_path('../' . $file->image));
                    }
                    $image = $request->file('image');
                    $path = get_date_path();
                    $imageName = $path . '/' . uniqid() . $file->id . '.' . $image->getClientOriginalExtension();
                    Storage::disk('files-image')->put($imageName, file_get_contents($image));
                    $file->update(['image' => 'storage/files-image/' . $imageName]);
                }

                if ($request->hasFile('file')) {
                    if ($file->image && file_exists(storage_path($file->file))) {
                        unlink(storage_path($file->file));
                    }
                    $f = $request->file('file');
                    $path = get_date_path();
                    $fileName = $path . '/' . uniqid() . $file->id . '.' . $f->getClientOriginalExtension();
                    Storage::disk('files')->put($fileName, file_get_contents($f));
                    $file->update(['file' => 'app/files/' . $fileName]);
                }

                return redirect()->route('admin-files')
                    ->with('alert', 'success')
                    ->with('message', lang('lang.changes_saved'));
            });
        } catch (\Exception $e) {
            return handle_exception($e);
        }
    }

    public function delete(Request $request, $id)
    {
        if (app('site_configs')['APP_ENV'] === 'demo') {
            return redirect()->back()
                ->with('alert', 'warning')
                ->with('message', lang('lang.demo_mode'));
        }

        $request->request->add(['id' => $id]);
        $rules = [
            'id' => 'required|exists:fp_files,id',
        ];
        $this->validate($request, $rules);

        $file = File::find($id);

        $file->update([
            'status' => File::$status['deleted'],
        ]);

        return redirect()->back()
            ->with('alert', 'success')
            ->with('message', lang('lang.changes_saved'));
    }
}
