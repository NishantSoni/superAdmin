@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Manage Two Factor Authentication
                    </div>

                    <div class="card-body">

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(empty($data['userTwoFactorInfo']) || $data['userTwoFactorInfo'] == null)

                            <form class="form-horizontal" method="POST" action="{{ route('generate2faSecret') }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">

                                        <p>Increase your account's security by enabling Google's Two-Factor Authentication (2FA).</p>

                                        <button type="submit" class="btn btn-primary">
                                            Generate Secret Key to Enable Google's 2FA
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @elseif(!$data['userTwoFactorInfo']->google2fa_enable)

                            <p>To Enable Two Factor Authentication on your Account, you need to do following steps</p>

                            <strong>
                                <ol>
                                    <li>Download Google Authenticator application on the device.</li>
                                    <li>Scan the below QR code.</li>
                                    <li>Authenticator Code is generated on your Google authenticator application.</li>
                                    <li>Enter the Authenticator Code.</li>
                                </ol>
                            </strong>
                            <hr/>
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <strong>Scan this QR code with your Google Authenticator App:</strong>
                                    <br/>
                                    <img src="{{$data['google2fa_url'] }}" alt="">
                                </div>
                                <div class="col-md-6">
                                    <strong>Enter the Authenticator Code to Enable 2FA</strong><br/><br/>
                                    <form class="form-horizontal" method="POST" action="{{ route('enable2fa') }}">
                                        {{ csrf_field() }}
                                        <div class="form-group{{ $errors->has('verify-code') ? ' has-error' : '' }}">
                                            <label for="verify-code" class="col-md-4 control-label">Authenticator Code</label>
                                            <div class="col-md-6">
                                                <input id="verify-code" type="password" class="form-control" name="verify-code" required>
                                                @if ($errors->has('verify-code'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('verify-code') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-md-offset-4">
                                                <button type="submit" class="btn btn-primary">
                                                    Enable 2FA
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @elseif($data['userTwoFactorInfo']->google2fa_enable)

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="alert alert-success">
                                        2FA is Currently <strong>Enabled</strong> for your account.
                                    </div>
                                    <p>If you are looking to disable Two Factor Authentication. Please Enter your Account password and Click Disable 2FA Button.</p>
                                    <form class="form-horizontal" method="POST" action="{{ route('disable2fa') }}">
                                        <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}">
                                            <label for="change-password" class="col-md-4 control-label">Current Password</label>
                                            <div class="col-md-6">
                                                <input id="current-password" type="password" class="form-control" name="current-password" required>
                                                @if ($errors->has('current-password'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('current-password') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-md-offset-5">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-primary ">Disable 2FA</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="col-md-6">

                                    @if($data['userTwoFactorBackupInfo'])

                                        <div class="alert alert-success">
                                            <p>Use these codes, in case you have lost your mobile.</p>
                                            <p>One backup code can be used only  once for security purpose.</p>
                                        </div>

                                        <div class="alert alert-info">
                                            <code>{{ $data['userTwoFactorBackupInfo'] }}</code>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <form class="form-horizontal" method="POST" action="{{ route('generate2FaBackups') }}">
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-primary ">Re-generate 2FA backup keys</button>
                                                </form>
                                            </div>
                                            <div class="col-md-6">
                                                <form class="form-horizontal" method="POST" action="{{ route('download2FaBackupCodes') }}">
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-primary ">Download 2FA backup keys</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <p>You can generate backup codes for 2FA.</p>
                                        <p>Backup codes are useful when you have lost your mobile phone.
                                        <form class="form-horizontal" method="POST" action="{{ route('generate2FaBackups') }}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-primary ">Generate 2FA backup keys</button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
