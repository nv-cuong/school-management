<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AddAdminRequest;
use App\Http\Requests\Admin\EditAdminRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class AdminController extends Controller
{
    public function index()
    {
        $header_title = "Admin List";
        $users = User::select('*')
            ->where('user_type', '=', 1)
            ->where('is_delete', '=', 0);

        if(!empty(Request::get('name'))) {
            $users = $users->where('name', 'LIKE', '%'.Request::get('name').'%');
        }
        if(!empty(Request::get('email'))) {
            $users = $users->where('email', 'LIKE', '%'.Request::get('email').'%');
        }
        if(!empty(Request::get('date_start'))) {
            $users = $users->whereDate('created_at', '>=', Request::get('date_start'));
        }
        if(!empty(Request::get('date_end'))) {
            $users = $users->whereDate('created_at', '<=', Request::get('date_end'));
        }

        $users = $users->orderBy('id', 'desc')
            ->paginate(10);

        return view('admin.admin.index', compact(['header_title', 'users']));
    }

    public function create()
    {
        $header_title = "Add new admin";
        
        return view('admin.admin.add', compact('header_title'));
    }

    public function store(AddAdminRequest $request)
    {
        User::create([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
            'user_type' => 3
        ]);
        
        return redirect()->route('admin.admin.index')->with('success','Admin created successfully');
    }

    public function edit($id)
    {
        $user = User::find($id);
        if(!empty($user)){
            $header_title = "Edit admin";
            return view('admin.admin.edit',compact(['user', 'header_title']));
        } else {
            abort(404);
        }
    }

    public function update(EditAdminRequest $request,$id)
    {
        $user = User::find($id);
        $user->name = trim($request->name);
        if(!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        
        return redirect()->route('admin.admin.index')->with('success','Admin updated successfully');

    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->update([
            $user->is_delete = 1
        ]);

        return redirect()->route('admin.admin.index')->with('success','Admin deleted successfully');
    }
}
