<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\ClassSubjectModel;
use App\Models\SubjectModel;
use App\Models\User;
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
            ->paginate(20);

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
            'is_delete' => 1,
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

    public function indexAssignSubject()
    {
        $header_title = "Assign subject list";
        $listAS = ClassSubjectModel::select('class_subject.*', 'class.name as class_name', 'subject.name as subject_name', 'users.name as user_name')
            ->join('subject', 'subject.id', 'class_subject.subject_id')
            ->join('class', 'class.id', 'class_subject.class_id')
            ->join('users', 'users.id', 'class_subject.who_created')
            ->where('class_subject.is_delete', '=', 1);

        if(!empty(Request::get('class_name'))) {
            $listAS = $listAS->where('class.name', 'LIKE', '%'.Request::get('class_name').'%');
        }
        if(!empty(Request::get('subject_name'))) {
            $listAS = $listAS->where('subject.name', 'LIKE', '%'.Request::get('subject_name').'%');
        }
        if(!empty(Request::get('status'))) {
            $listAS = $listAS->where('status', '=', Request::get('status'));
        }
        if(!empty(Request::get('type'))) {
            $listAS = $listAS->where('class_subject.type', '=', Request::get('type'));
        }
        // if(!empty(Request::get('who_created'))) {
        //     $listAS = $listAS->where('who_created', '=', Request::get('who_created'));
        // }
        if(!empty(Request::get('date_start'))) {
            $listAS = $listAS->whereDate('class_subject.created_at', '>=', Request::get('date_start'));
        }
        if(!empty(Request::get('date_end'))) {
            $listAS = $listAS->whereDate('class_subject.created_at', '<=', Request::get('date_end'));
        }

        $listAS = $listAS->orderBy('class_subject.id','desc')
            ->paginate(20);

        return view('admin.assign_subject.index', compact(['header_title', 'listAS']));
    }

    public function createAssignSubject()
    {
        $header_title = "Assign subject add";

        $listClass = ClassModel::getClass();
        $listSubject = SubjectModel::getSubject();

        return view('admin.assign_subject.add', compact(['header_title', 'listClass', 'listSubject']));
    }

    public function storeAssignSubject(HttpRequest $request)
    {
        if(!empty($request->subject_id))
        {
            foreach($request->subject_id as $subject_id)
            {
                $getClassSubject = ClassSubjectModel::where('class_id', '=', $request->class_id)
                    ->where('subject_id', '=', $subject_id)->first();
                if(!empty($getClassSubject))
                {
                    $getClassSubject->status = $request->status;
                    $getClassSubject->save();
                }
                else
                {
                    ClassSubjectModel::create([
                        'class_id' => $request->class_id,
                        'subject_id' => $subject_id,
                        'status' => $request->status,
                        'is_delete' => 1,
                        'who_created' => Auth::user()->id,
                        'who_updated' => Auth::user()->id
                    ]);
                }
            }
            return redirect()->route('admin.assign_subject.index')->with('success', "Subject successfully assign to class");
        } 
        else 
        {
            return redirect()->route('admin.assign_subject.index')->with('error', "Due to some error please try again");
        }
    }

    public function deleteAssignSubject($id)
    {
        $classSubject = ClassSubjectModel::find($id);
        $classSubject->update([
            'is_delete' => 2,
            'who_updated' => Auth::user()->id
        ]);

        return redirect()->route('admin.assign_subject.index')->with('success','Record successfully deleted');
    }

    public function editAssignSubject($id)
    {
        $classSubject = ClassSubjectModel::find($id);
        if(!empty($classSubject)){
            $header_title = "Assign subject edit";

            $getAssignSubjectID = ClassSubjectModel::where("class_id", '=', $classSubject->class_id)
                ->where('is_delete', '=', 1)->get();

            $listClass = ClassModel::getClass();
            $listSubject = SubjectModel::getSubject();
            
            return view('admin.assign_subject.edit', compact(['header_title', 'listClass', 'listSubject', 'classSubject', 'getAssignSubjectID']));
        } else {
            abort(404);
        }
    }

    public function updateAssignSubject($id, HttpRequest $request)
    {
        $classSubject = ClassSubjectModel::find($id);
        $classSubject->where('class_id', '=', $request->class_id)->delete();
        if(!empty($request->subject_id))
        {
            foreach($request->subject_id as $subject_id)
            {
                $getClassSubject = ClassSubjectModel::where('class_id', '=', $request->class_id)
                    ->where('subject_id', '=', $subject_id)->first();
                if(!empty($getClassSubject))
                {
                    $getClassSubject->status = $request->status;
                    $getClassSubject->who_updated = Auth::user()->id;
                    $getClassSubject->save();
                }
                else
                {
                    ClassSubjectModel::create([
                        'class_id' => $request->class_id,
                        'subject_id' => $subject_id,
                        'status' => $request->status,
                        'is_delete' => 1,
                        'who_created' => Auth::user()->id,
                        'who_updated' => Auth::user()->id
                    ]);
                }
            }
            return redirect()->route('admin.assign_subject.index')->with('success', "Subject successfully assign to class");
        }
    }

    public function editSingleAssignSubject($id)
    {
        $classSubject = ClassSubjectModel::find($id);
        if(!empty($classSubject)){
            $header_title = "Assign subject edit";

            $getAssignSubjectID = ClassSubjectModel::where("class_id", '=', $classSubject->class_id)
                ->where('is_delete', '=', 1)->get();

            $listClass = ClassModel::getClass();
            $listSubject = SubjectModel::getSubject();
            
            return view('admin.assign_subject.edit_single', compact(['header_title', 'listClass', 'listSubject', 'classSubject', 'getAssignSubjectID']));
        } else {
            abort(404);
        }
    }

    public function updateSingleAssignSubject($id, HttpRequest $request)
    {
        $getClassSubject = ClassSubjectModel::where('class_id', '=', $request->class_id)
                ->where('subject_id', '=', $request->subject_id)->first();
        if(!empty($getClassSubject))
        {
            $getClassSubject->status = $request->status;
            $getClassSubject->who_updated = Auth::user()->id;
            $getClassSubject->save();

            return redirect()->route('admin.assign_subject.index')->with('success', "Status successfully updated");
        }
        else
        {
            $update = ClassSubjectModel::find($id);
            $update->update([
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'status' => $request->status,
                'who_updated' => Auth::user()->id
            ]);

            return redirect()->route('admin.assign_subject.index')->with('success', "Subject successfully assign to class");
        }
    }
}
