@extends('layouts.Admin')
@section('content')
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-md-12">
                <section class="panel">
@include('includes.DashboardMessages')
                    <ul class="breadcrumb">
                        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
                        <li class="active"><a href="/">Bread Crumbs Example</a></li>
                    </ul>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12">
                                EXAMPLE
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</section>
@stop
