@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Admin List</h1>
                    </div>
                    <div class="col-sm-6" style="text-align: right;">
                        <a href="{{ route('admin.admin.create') }}" class="btn btn-success"><i class="fas fa-plus mr-1"></i>Add
                            new admin
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
                                        <div class="form-group col-md-3">
                                            <label>Name</label>
                                            <input type="text" class="form-control" value="{{ Request::get('name') }}" name="name" placeholder="Enter name">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Email address</label>
                                            <input type="text" class="form-control" value="{{ Request::get('email') }}" name="email" placeholder="Enter email">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Start date</label>
                                            <input type="date" class="form-control" value="{{ Request::get('date_start') }}" name="date_start" placeholder="Enter start date">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>End date</label>
                                            <input type="date" class="form-control" value="{{ Request::get('date_end') }}" name="date_end" placeholder="Enter end date">
                                        </div>
                                    </div>                                  
                                    <div class="row d-flex justify-content-center">
                                        <button type="submit" class="btn btn-info mr-5 button-100"><i class="fas fa-search mr-1"></i>Search</button>
                                        <a class="btn btn-secondary button-100" href="{{ route('admin.admin.index') }}"><i class="fas fa-undo mr-1"></i>Reset</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">

                        @include('_message')

                        <div class="card">
                            <div class="card-header card-blue-1">
                                <h3 class="card-title">Admin list</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>When create</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ date('d/m/Y h:i:s A', strtotime($user->created_at)) }}</td>
                                                <td>
                                                    <a class="btn btn-primary"
                                                        href="{{ route('admin.admin.edit', $user->id) }}" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <a class="btn btn-danger"
                                                        href="{{ route('admin.admin.delete', $user->id) }}"
                                                        title="Delete"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {!! $users->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
