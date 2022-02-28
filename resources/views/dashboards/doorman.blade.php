@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Dashboard</h3>
        <div class="row">
            <div class="col-sm-2">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Show overnight stays</h5>
                        <a href="{{route('overnights.index')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>
            <!--
            <div class="col-sm-2">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Show my breakage reports</h5>
                        <a href="#" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>
        
            <div class="col-sm-2">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Report a breakage </h5>
                        <a href="#" class="btn btn-primary">Report</a>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-2">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Loaned things </h5>
                        <a href="#" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-2">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Colleges</h5>
                        <a href="#" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-2">
            <div class="card text-white bg-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Dorm workers</h5>
                        <a href="#" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Dorm tenants</h5>
                        <a href="#" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>  -->  
        </div>
    </div>
</div>
@endsection
