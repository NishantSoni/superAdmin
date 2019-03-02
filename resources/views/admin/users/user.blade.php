@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="h5">
                            @if(isset($user) && (Auth::id() == $user->id))
                                {{ __('Personal Information Page') }}
                            @elseif(isset($user))
                                {{ __('Edit User Account') }}
                            @else
                                {{ __('Add User Account') }}
                            @endif
                        </span>
                    </div>

                    <div class="card-body">

                        @if (session('errorMessage'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ session('errorMessage') }}</strong>
                            </div>
                        @endif

                        @if(isset($user))
                            <form method="POST" action="{{ route('users.update', $user->id) }}" id="UserForm">
                            @method('PATCH')
                        @else
                            <form method="POST" action="{{ route('users.store') }}" id="UserForm">
                        @endif

                            @csrf

                            <div class="form-group row justify-content-md-center">
                                <label for="username" class="col-md-2 col-form-label ">{{ __('Username') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ isset($user) ? $user->username : old('username') }}" required autofocus>

                                    @if ($errors->has('username'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="email" class="col-md-2 col-form-label ">{{ __('E-Mail Address') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ isset($user) ? $user->email : old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="password" class="col-md-2 col-form-label ">{{ __('Password') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" {{ isset($user) ? '' : 'required' }}>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="password-confirm" class="col-md-2 col-form-label ">{{ __('Confirm Password') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" {{ isset($user) ? '' : 'required' }}>
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="first_name" class="col-md-2 col-form-label ">{{ __('First Name') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ isset($user) ? $user->first_name : old('first_name') }}" required autofocus>

                                    @if ($errors->has('first_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="last_name" class="col-md-2 col-form-label ">{{ __('Last Name') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ isset($user) ? $user->last_name : old('last_name') }}" required autofocus>

                                    @if ($errors->has('last_name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="address" class="col-md-2 col-form-label ">{{ __('Address') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <textarea rows="2" cols="50" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" name="address" required autofocus> {{ isset($user) ? $user->address : old('address') }}</textarea>

                                    @if ($errors->has('address'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="house_number" class="col-md-2 col-form-label ">{{ __('House Number') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <input id="house_number" type="text" class="form-control{{ $errors->has('house_number') ? ' is-invalid' : '' }}" name="house_number" value="{{ isset($user) ? $user->house_number : old('house_number') }}" required autofocus>

                                    @if ($errors->has('house_number'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('house_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="postal_code" class="col-md-2 col-form-label ">{{ __('Postal Code') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <input id="postal_code" type="text" class="form-control{{ $errors->has('postal_code') ? ' is-invalid' : '' }}" name="postal_code" value="{{ isset($user) ? $user->postal_code : old('postal_code') }}" required autofocus>

                                    @if ($errors->has('postal_code'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('postal_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="city" class="col-md-2 col-form-label ">{{ __('City') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <input id="city" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ isset($user) ? $user->city : old('city') }}" required autofocus>

                                    @if ($errors->has('city'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="telephone_number" class="col-md-2 col-form-label ">{{ __('Telephone number') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4">
                                    <input id="telephone_number" type="text" class="form-control{{ $errors->has('telephone_number') ? ' is-invalid' : '' }}" name="telephone_number" value="{{ isset($user) ? $user->telephone_number :  old('telephone_number') }}" required autofocus>

                                    @if ($errors->has('telephone_number'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('telephone_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row justify-content-md-center">
                                <label for="is_active" class="col-md-2 col-form-label ">{{ __('Activate User') }} <span class="text-danger">*</span></label>

                                <div class="col-md-4 checkbox-field">

                                    <input id="is_active" type="checkbox" class="form-control{{ $errors->has('is_active') ? ' is-invalid' : '' }}" name="is_active" value="1"  {{ isset($user) ? (($user->is_active) ? 'checked' : '') :  'checked' }}>

                                    @if ($errors->has('is_active'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('is_active') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save') }}
                                    </button>

                                    <button type="button" class="btn btn-primary" onClick="resetFormFields();">
                                        Reset
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
