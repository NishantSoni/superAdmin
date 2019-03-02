@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="h5">
                            User Exports Management
                        </span>
                    </div>

                    <div class="card-body">

                        @if (file_exists($pathToFile))
                            <a class="btn btn-primary" href="{{ route('usersDownload') }}" >
                                Click here to download exported users
                            </a>
                        @else
                            <div class="alert alert-danger">
                                Please click on "Export Users" button on "User Management" page , then you will be able to download CSV file.
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
