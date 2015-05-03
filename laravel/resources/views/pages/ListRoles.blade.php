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
<?php if (!empty($Roles)) { ?>
                                <div class="adv-table">
                                    <table class="display table table-bordered table-striped" id="listRoles">
                                        <thead>
                                        <tr>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody>
    <?php foreach ($Roles as $role) { ?>
                                            <tr class="gradeX">
                                                <td>{{{ $role->label }}}</td>
            <?php if ($role->isActive) { ?>
                                                <td><span class="label label-success label-mini">Active</span></td>
            <?php } else { ?>
                                                <td><span class="label label-danger label-mini">Inactive</span></td>
            <?php } ?>
                                                <td><a href="/Admin/editRole/{{{ $role->role_id }}}"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></button></a></td>
                                            </tr>
    <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
<?php } else { ?>
                                <p>No role records exist.</p>
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
