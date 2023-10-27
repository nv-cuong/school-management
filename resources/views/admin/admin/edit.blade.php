@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Admin</h1>
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
                                <h3 class="card-title">Edit admin</h3>
                            </div>
                            <form method="post" action="{{ route('admin.admin.update', $user->id) }}">
                                {{ csrf_field() }}
                                @method('put')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name', $user->name) }}" placeholder="Enter name">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email address</label>
                                        <input type="email" class="form-control" name="email" value="{{ $user->email }}"
                                            disabled readonly placeholder="Enter email">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="text" class="form-control" name="password" placeholder="Password">
                                        <p>Do you want to change password so Please add new password</p>
                                    </div>
                                </div>

                                <div class="card-footer row d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success mr-5" style="width: 100px">Update</button>
                                    <a class="btn btn-secondary" style="width: 100px"
                                        href="{{ route('admin.admin.index') }}">Back</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
