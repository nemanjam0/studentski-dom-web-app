@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Add</h3>
        <div class="row">
            <div class="col-sm-2">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Add new dorm tenant</h5>
                        <a href="{{route('dormstudents.create')}}" class="btn btn-primary">Add</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Show overnight stays</h5>
                        <a href="{{route('overnights.index')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
        </div>
        </div>
    <h3>Show all</h3>
        <div class="row">
            <div class="col-sm-2">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Dorm tenants</h5>
                        <a href="{{route('dormstudents.index')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
