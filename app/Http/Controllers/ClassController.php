<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ClassController extends Controller
{
    public function index()
    {
        $header_title = "Class list";
        $classes = ClassModel::select('*')
            ->with('user')
            ->where('is_delete', '=', 0);

        if(!empty(Request::get('name'))) {
            $classes = $classes->where('name', 'LIKE', '%'.Request::get('name').'%');
        }
        if(!empty(Request::get('status'))) {
            $classes = $classes->where('status', '=', Request::get('status'));
        }
        if(!empty(Request::get('who_created'))) {
            $classes = $classes->where('who_created', '=', Request::get('who_created'));
        }
        if(!empty(Request::get('date_start'))) {
            $classes = $classes->whereDate('created_at', '>=', Request::get('date_start'));
        }
        if(!empty(Request::get('date_end'))) {
            $classes = $classes->whereDate('created_at', '<=', Request::get('date_end'));
        }

        $classes = $classes->orderBy('id', 'desc')
            ->paginate(10);

        // list user (Admin + Teacher)
        $users = User::select('id', 'name')
            ->whereIn('user_type', [1, 2])
            ->get();

        return view('admin.class.index', compact(['header_title', 'classes', 'users']));
    }

    public function create()
    {
        $header_title = "Add new class";
        
        return view('admin.class.add', compact('header_title'));
    }

    public function store(HttpRequest $request)
    {
        ClassModel::create([
            'name' => trim($request->name),
            'status' => $request->status,
            'is_delete' => 0,
            'who_created' => Auth::user()->id,
            'who_updated' => Auth::user()->id
        ]);
        
        return redirect()->route('admin.class.index')->with('success','Class created successfully');
    }

    public function edit($id)
    {
        $class = ClassModel::find($id);
        if(!empty($class)){
            $header_title = "Edit class";
            return view('admin.class.edit',compact(['class', 'header_title']));
        } else {
            abort(404);
        }
    }

    public function update(HttpRequest $request, $id)
    {
        $class = ClassModel::find($id);
        $class->update([
            'name' => trim($request->name),
            'status' => $request->status,
            'who_updated' => Auth::user()->id
        ]);
        
        return redirect()->route('admin.class.index')->with('success','Class updated successfully');

    }

    public function delete($id)
    {
        $class = ClassModel::find($id);
        $class->update([
            'is_delete' => 1,
            'who_updated' => Auth::user()->id
        ]);

        return redirect()->route('admin.class.index')->with('success','Class deleted successfully');
    }
}
