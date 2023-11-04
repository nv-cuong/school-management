@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Assign subject</h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit assign subject to class</h3>
                            </div>
                            <form method="post" action="{{ route('admin.assign_subject.update_single', $classSubject->id) }}">
                                {{ csrf_field() }}
                                @method('put')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Class name <span class="star-dot">*</span></label>
                                        <select class="form-control" name="class_id">
                                            <option value="">Select class</option>
                                            @foreach ($listClass as $class)
                                                <option {{ ($classSubject->class_id == $class->id) ? 'selected' : '' }} value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Subject name <span class="star-dot">*</span></label>
                                        <select class="form-control" name="subject_id">
                                            <option value="">Select subject</option>
                                            @foreach ($listSubject as $subject)
                                                <option {{ ($classSubject->subject_id == $subject->id) ? 'selected' : '' }} value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Status <span class="star-dot">*</span></label>
                                        <select class="form-control" name="status">
                                            <option {{ ($classSubject->status == 1) ? 'selected' : '' }} value="1">Active</option>
                                            <option {{ ($classSubject->status == 2) ? 'selected' : '' }} value="2">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card-footer row d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success mr-5 button-100">Update</button>
                                    <a class="btn btn-secondary button-100"
                                        href="{{ route('admin.assign_subject.index') }}">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
