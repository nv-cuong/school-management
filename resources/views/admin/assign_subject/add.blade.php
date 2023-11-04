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
                                <h3 class="card-title">Assign subject to class</h3>
                            </div>
                            <form method="post" action="{{ route('admin.assign_subject.store') }}">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Class name <span class="star-dot">*</span></label>
                                        <select class="form-control" name="class_id">
                                            <option value="">Select class</option>
                                            @foreach ($listClass as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Subject name <span class="star-dot">*</span></label>
                                        @foreach ($listSubject as $subject)
                                            <div class="col-md-3">
                                                <label style="font-weight: normal;">
                                                    <input type="checkbox" value="{{ $subject->id }}" name="subject_id[]"> {{ $subject->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label>Status <span class="star-dot">*</span></label>
                                        <select class="form-control" name="status">
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card-footer row d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success mr-5" style="width: 100px">Add</button>
                                    <a class="btn btn-secondary" style="width: 100px"
                                        href="{{ route('admin.class.index') }}">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
