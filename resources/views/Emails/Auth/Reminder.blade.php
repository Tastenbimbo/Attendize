@extends('Emails.Layouts.Master')

@section('message_content')
    <div>
        {{trans('attendize.general.hello')}},<br><br>
        {{trans('attendize.general.password_reset')}}{{ route('showResetPassword', ['token' => $token]) }}.
        <br><br><br>
        {{trans('attendize.general.thanks')}},<br>
        Team Attendize
    </div>
@stop