@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Subject List</h1>
                    </div>
                    <div class="col-sm-6" style="text-align: right;">
                        <a href="{{ route('admin.subject.create') }}" class="btn btn-success"><i class="fas fa-plus mr-1"></i>Add
                            new subject
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header card-green-1">
                                <h3 class="card-title">Search for information</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="get" action="">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <label>Name subject</label>
                                            <input type="text" class="form-control" value="{{ Request::get('name') }}" name="name" placeholder="Enter class">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Status</label>
                                            <select class="form-control" name="status">
                                                <option disabled selected>Select status</option>
                                                <option {{ Request::get('status') == 1 ? 'selected' : '' }} value="1">Active</option>
                                                <option {{ Request::get('status') == 2 ? 'selected' : '' }} value="2">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Status</label>
                                            <select class="form-control" name="type">
                                                <option disabled selected>Select type</option>
                                                <option {{ Request::get('type') == 'Theory' ? 'selected' : '' }} value="Theory">Theory</option>
                                                <option {{ Request::get('type') == 'Practical' ? 'selected' : '' }} value="Practical">Practical</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Who created</label>
                                            <select class="form-control" name="who_created">
                                                <option disabled selected>Select user</option>
                                                @foreach ($users as $user)
                                                    <option {{ Request::get('who_created') == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>Start date</label>
                                            <input type="date" class="form-control" value="{{ Request::get('date_start') }}" name="date_start" placeholder="Enter start date">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label>End date</label>
                                            <input type="date" class="form-control" value="{{ Request::get('date_end') }}" name="date_end" placeholder="Enter end date">
                                        </div>
                                    </div>                                  
                                    <div class="row d-flex justify-content-center">
                                        <button type="submit" class="btn btn-info mr-5 button-100"><i class="fas fa-search mr-1"></i>Search</button>
                                        <a class="btn btn-secondary button-100" href="{{ route('admin.subject.index') }}"><i class="fas fa-undo mr-1"></i>Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">

                        @include('_message')

                        <div class="card">
                            <div class="card-header card-blue-1">
                                <h3 class="card-title">Subject list</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject name</th>
                                            <th>Subject type</th>
                                            <th>Status</th>
                                            <th>Who created</th>
                                            <th>When created</th>
                                            <th>Who updated</th>
                                            <th>When updated</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subjects as $subject)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $subject->name }}</td>
                                                <td>{{ $subject->type }}</td>
                                                <td>
                                                    @if ($subject->status == 1)
                                                        Active
                                                    @else
                                                        Inactive
                                                    @endif  
                                                </td>
                                                <td>{{ $subject->user->name }}</td>
                                                <td>{{ date('d/m/Y h:i:s A', strtotime($subject->created_at)) }}</td>
                                                <td>{{ $subject->user->name }}</td>
                                                <td>{{ date('d/m/Y h:i:s A', strtotime($subject->updated_at)) }}</td>
                                                <td>
                                                    <a class="btn btn-primary"
                                                        href="{{ route('admin.subject.edit', $subject->id) }}" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <a class="btn btn-danger"
                                                        href="{{ route('admin.subject.delete', $subject->id) }}"
                                                        title="Delete"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {!! $subjects->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
