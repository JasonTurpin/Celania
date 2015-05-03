@extends('layouts.Default')
@section('content')
<form class="form-signin" action="{{{ $_pageAction }}}" method="post">
    <h2 class="form-signin-heading">Sign In</h2>
    <div class="login-wrap">
        <input type="text" class="form-control" placeholder="User Name" name="ds*3dddx" value="{{{ $username }}}" autofocus />
        <input type="password" class="form-control" placeholder="Password" name="psa#9ccc">
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
        <?php
        /*
        <label class="checkbox">
            <span class="pull-right">
                <a data-toggle="modal" href="#myModal"> Forgot Password?</a>
            </span>
        </label>
        */
        ?>
        <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
</form>
<?php
/*
<form class="form-signin" action="/Home/ForgotPassword" method="post">
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Forgot Password</h4>
                </div>
                <div class="modal-body">
                    <p>Enter your e-mail address below to reset your password.</p>
                    <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                    <button class="btn btn-success" type="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>
*/
?>
@stop
