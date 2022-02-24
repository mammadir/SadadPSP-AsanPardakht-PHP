<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::where('status', '=', Form::$status['active'])->orderBy('id', 'desc')->paginate(15);

        return view('fp::admin.forms.index')
            ->with('activeMenu', 'forms')
            ->with('forms', $forms);
    }

    public function showAdd()
    {
        return view('fp::admin.forms.add')
            ->with('activeMenu', 'forms');
    }

    public function add(Request $request)
    {
        $rules = [
            'title' => 'required|max:255',
            'amount' => 'numeric|greater_than_rial:1000',
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
                $form = Form::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'amount' => $request->amount,
                    'pay_limit' => $request->pay_limit,
                    'fields' => $fields,
                    'form_size' => $request->form_size ? $request->form_size : 4
                ]);

                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $path = get_date_path();
                    $imageName = $path . '/' . uniqid() . $form->id . '.' . $image->getClientOriginalExtension();
                    Storage::disk('forms')->put($imageName, file_get_contents($image));
                    $form->update(['image' => 'storage/forms/' . $imageName]);
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
            'id' => 'required|exists:fp_forms,id',
        ];
        $this->validate($request, $rules);

        $form = Form::find($id);

        return view('fp::admin.forms.edit')
            ->with('activeMenu', 'forms')
            ->with('form', $form);
    }

    public function edit(Request $request, $id)
    {
        $request->request->add(['id' => $id]);
        $rules = [
            'id' => 'required|exists:fp_forms,id',
            'title' => 'required',
            'image' => 'image',
        ];
        $this->validate($request, $rules);

        try {
            return DB::transaction(function () use ($request, $id) {
                $form = Form::find($id);
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

                $form->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'amount' => $request->amount,
                    'pay_limit' => $request->pay_limit,
                    'fields' => $fields,
                    'form_size' => $request->form_size ? $request->form_size : 4
                ]);

                if ($request->hasFile('image')) {
                    if ($form->image && file_exists(base_path('../' . $form->image))) {
                        unlink(base_path('../' . $form->image));
                    }
                    $image = $request->file('image');
                    $path = get_date_path();
                    $imageName = $path . '/' . uniqid() . $form->id . '.' . $image->getClientOriginalExtension();
                    Storage::disk('forms')->put($imageName, file_get_contents($image));
                    $form->update(['image' => 'storage/forms/' . $imageName]);
                }

                return redirect()->route('admin-forms')
                    ->with('alert', 'success')
                    ->with('message', lang('lang.changes_saved'));
            });
        } catch (\Exception $e) {
            return handle_exception($e);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function makeDefault(Request $request, $id)
    {
        if (app('site_configs')['APP_ENV'] === 'demo') {
            return redirect()->back()
                ->with('alert', 'warning')
                ->with('message', lang('lang.demo_mode'));
        }

        $request->request->add(['id' => $id]);
        $rules = [
            'id' => 'required|exists:fp_forms,id',
        ];
        $this->validate($request, $rules);

        $form = Form::find($id);

        try {
            return DB::transaction(function () use ($form) {
                Form::where('id', '>', 0)->update([
                    'default' => 0,
                ]);
                $form->update(['default' => 1]);

                return redirect()->back()
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
            'id' => 'required|exists:fp_forms,id',
        ];
        $this->validate($request, $rules);

        $form = Form::find($id);

        if ($form->default) {
            return redirect()->back()
                ->with('alert', 'danger')
                ->with('message', lang('lang.cannot_delete_default_form'));
        }

        $form->update([
            'status' => Form::$status['deleted'],
        ]);

        return redirect()->back()
            ->with('alert', 'success')
            ->with('message', lang('lang.changes_saved'));
    }
}
