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
                        <li class="active"><a href="{{{$_pageAction }}}">{{{ $_pageName }}}</a></li>
                    </ul>
                    <header class="panel-heading">{{{ $_pageName }}}</header>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">

<?php if (!empty($Users)) { ?>
                                <div class="adv-table">
                                    <table class="display table table-bordered table-striped" id="listUsers">
                                        <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Username</th>
                                            <th>Status</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($Users as $user) { ?>
                                            <tr class="gradeX">
                                                <td>{{{ $user->firstName }}}</td>
                                                <td>{{{ $user->lastName }}}</td>
                                                <td>{{{ $user->username }}}</td>
        <?php if ($user->isActive) { ?>
                                                <td><span class="label label-success label-mini">Active</span></td>
        <?php } else { ?>
                                                <td><span class="label label-danger label-mini">Inactive</span></td>
        <?php } ?>
                                                <td><a href="/Admin/editUser/{{{ $user->user_id }}}"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button></a></td>
                                            </tr>
    <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
<?php } else { ?>
                                <p>No user records exist.</p>
<?php } ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>
@stop
