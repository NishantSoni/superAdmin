@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <span class="float-sm-left h5">
                                User Management
                            </span>
                            <a class="btn btn-primary float-sm-right" style="margin-left: 10px;" href="{{ route('users.create') }}" >
                                Create User Account
                            </a>
                            <a class="btn btn-primary float-sm-right" href="{{ route('usersExport') }}" >
                                Export Users
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('successMessage'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session('successMessage') }}</strong>
                            </div>
                        @endif

                        @if (session('errorMessage'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session('errorMessage') }}</strong>
                            </div>
                        @endif

                        {{ $table  }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
