@extends('layouts.Admin')
@section('content')
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <section class="panel">
@include('includes.DashboardMessages')
                    <ul class="breadcrumb">
                        <li><a href="/Admin"><i class="fa fa-home"></i> Home</a></li>
                        <li><a href="/Admin/listUsers">List Users</a></li>
                        <li class="active"><a href="{{{$_pageAction }}}">{{{ $_pageName }}}</a></li>
                    </ul>
                    <header class="panel-heading">{{{ $_pageName }}}</header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <form role="form" class="form-horizontal" method="post" action="{{{ $_pageAction }}}">
<?php if ($Roles->count() > 0) { ?>
                                    <div class="col-sm-4 pull-right">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Roles</label>
                                            <div class="col-lg-8">
                                                <div class="input-group m-bot15">
                                                    <ul class="inputList">
    <?php foreach ($Roles as $role) { ?>
                                                        <li>
                                                            <label><input type="checkbox" name="roles[]" value="{{{ $role->role_id }}}" <?php echo
                                                                (in_array($role->role_id, $userRoleData)
                                                                    ? ' checked'
                                                                    : '');
                                                                ?>/> {{{ $role->label }}}</label>
                                                        </li>
    <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
<?php } ?>

                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">First Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="firstName" value="{{{ $user->firstName }}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Last Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="lastName" value="{{{ $user->lastName }}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Username</label>
                                            <div class="col-sm-10">
<?php if ($_action == 'edituser') { ?>
                                                <p>{{{ $user->username }}}</p>
<?php } else { ?>
                                                <input type="text" class="form-control" name="username" value="{{{ $user->username }}}" autocomplete="off">
<?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
<?php if ($_action == 'edituser') { ?>
                                                    <p>{{{ $user->email }}}</p>
<?php } else { ?>
                                                    <input type="text" class="form-control" name="email" value="{{{ $user->email }}}" autocomplete="off">
<?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="password" autocomplete="off">
                                                <br />
                                                @include('includes.PasswordStrengthInstructions')
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 col-sm-2 control-label">Confirm Password</label>
                                            <div class="col-sm-10">
                                                <input type="password" class="form-control" name="password2" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputSuccess" class="col-sm-2 control-label col-lg-2">Active</label>
                                            <div class="col-lg-10">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" value="1" name="isActive"<?php echo
                                                            ($user->isActive == '1' || is_null($user->isActive)
                                                                ? ' checked'
                                                                : '');
                                                        ?>> Yes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" value="0" name="isActive"<?php echo
                                                            ($user->isActive == '0'
                                                                ? ' checked'
                                                                : '');
                                                        ?>> No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="pull-right col-sm-1">
                                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                                                <button class="btn btn-success" type="submit">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>
@stop
