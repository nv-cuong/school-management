<?php

namespace App\Http\Controllers;

use App\Models\SubjectModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $header_title = "Subject list";
        $subjects = SubjectModel::select('*')
            ->with('user')
            ->where('is_delete', '=', 0);

        if(!empty(Request::get('name'))) {
            $subjects = $subjects->where('name', 'LIKE', '%'.Request::get('name').'%');
        }
        if(!empty(Request::get('status'))) {
            $subjects = $subjects->where('status', '=', Request::get('status'));
        }
        if(!empty(Request::get('type'))) {
            $subjects = $subjects->where('type', '=', Request::get('type'));
        }
        if(!empty(Request::get('who_created'))) {
            $subjects = $subjects->where('who_created', '=', Request::get('who_created'));
        }
        if(!empty(Request::get('date_start'))) {
            $subjects = $subjects->whereDate('created_at', '>=', Request::get('date_start'));
        }
        if(!empty(Request::get('date_end'))) {
            $subjects = $subjects->whereDate('created_at', '<=', Request::get('date_end'));
        }

        $subjects = $subjects->orderBy('id', 'desc')
            ->paginate(10);

        // list user (Admin + Teacher)
        $users = User::select('id', 'name')
            ->whereIn('user_type', [1, 2])
            ->get();

        return view('admin.subject.index', compact(['header_title', 'subjects', 'users']));
    }

    public function create()
    {
        $header_title = "Add new subject";
        
        return view('admin.subject.add', compact('header_title'));
    }

    public function store(HttpRequest $request)
    {
        SubjectModel::create([
            'name' => trim($request->name),
            'type' => trim($request->type),
            'status' => $request->status,
            'is_delete' => 0,
            'who_created' => Auth::user()->id,
            'who_updated' => Auth::user()->id
        ]);
        
        return redirect()->route('admin.subject.index')->with('success','Subject created successfully');
    }

    public function edit($id)
    {
        $subject = SubjectModel::find($id);
        if(!empty($subject)){
            $header_title = "Subject class";
            return view('admin.subject.edit',compact(['subject', 'header_title']));
        } else {
            abort(404);
        }
    }

    public function update(HttpRequest $request, $id)
    {
        $subject = SubjectModel::find($id);
        $subject->update([
            'name' => trim($request->name),
            'type' => trim($request->type),
            'status' => $request->status,
            'who_updated' => Auth::user()->id
        ]);
        
        return redirect()->route('admin.subject.index')->with('success','Subject updated successfully');
    }

    public function delete($id)
    {
        $subject = SubjectModel::find($id);
        $subject->update([
            'is_delete' => 2,
            'who_updated' => Auth::user()->id
        ]);

        return redirect()->route('admin.subject.index')->with('success','Subject deleted successfully');
    }
}
