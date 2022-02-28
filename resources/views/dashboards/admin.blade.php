@extends('layouts.app')
@section('content')
<div class="container">
@if($invoices_error!="")
    <div class="alert alert-danger">
        {{$invoices_error}}
    </div>
@endif
    <h3>Add</h3>
        <div class="row">
            <div class="col-sm-2">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Add new user</h5>
                        <a href="{{route('users.create')}}" class="btn btn-primary">Add</a>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-2">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Add new student</h5>
                        <a href="{{route('students.create')}}" class="btn btn-primary">Add</a>
                    </div>
                </div>
            </div>
        
            <div class="col-sm-2">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Add new dorm</h5>
                        <a href="{{route('dorms.create')}}" class="btn btn-primary">Add</a>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-2">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Add new room</h5>
                        <a href="{{route('rooms.create')}}" class="btn btn-primary">Add</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Add new college</h5>
                        <a href="{{route('colleges.create')}}" class="btn btn-primary">Add</a>
                    </div>
                </div>
            </div>
                <div class="col-sm-2">
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Dorm tenants</h5>
                            <a href="{{route('dormstudents.create')}}" class="btn btn-primary">Add</a>
                        </div>
                    </div>
                </div>    
        </div>
        <div class="row">
            <div class="col-sm-2">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Add new worker to dorm</h5>
                            <a href="{{route('workers.create')}}" class="btn btn-primary">Add</a>
                        </div>
                    </div>
            </div>
            <div class="col-sm-2">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Create new card type</h5>
                            <a href="{{route('cards.create')}}" class="btn btn-primary">Create</a>
                        </div>
                    </div>
            </div>
            <div class="col-sm-2">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Issue new card</h5>
                            <a href="{{route('studentcards.create')}}" class="btn btn-primary">Issue</a>
                        </div>
                    </div>
            </div>
            <div class="col-sm-2">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Create new invoice</h5>
                            <select id="invoices" class="form-control" onchange="invoice_function()">
                                <option value="" selected disabled hidden>Select</option>
                                <option value="template">Template</option>
                                <option value="category">Category</option>
                                <option value="dorm">Dorm</option>
                                <option value="room">Room</option>
                                </select>
                        </div>
                    </div>
            </div>
            

            <div class="col-sm-2">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Renew card</h5>
                            <form method="POST" action="{{ route('studentcard.renew') }}">
                                @csrf
                                <div class="form-group row">

                                    <label for="student_card" class="col-mb-3 col-form-label text-md-right">{{ __('Student card number') }}</label>

                                    <div class="col-mb-3">
                                        <input id="student_card" type="text" class="form-control @error('student_card') is-invalid @enderror" name="student_card" value="{{ old('student_card') }}">
                                        @error('student_card')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-mb-3">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Renew') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                     </div>
                </div>
                
            </div> 
            <div class="col-sm-2">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-body">
                            <h5 class="card-title">View invoice log</h5>
                            <a href="{{route('invoice_log.index')}}" class="btn btn-primary">Display</a>
                        </div>
                    </div>
            </div>  
        </div>
        
    <h3>Show all</h3>
        <div class="row">
            <div class="col-sm-2">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <a href="{{route('users.index')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-2">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Students</h5>
                        <a href="{{route('students.index')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>
        
            <div class="col-sm-2">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Dorms</h5>
                        <a href="{{route('dorms.index')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>
            
            <div class="col-sm-2">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Rooms</h5>
                        <a href="{{route('rooms.index')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Colleges</h5>
                        <a href="{{route('colleges.index')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-2">
            <div class="card text-white bg-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Dorm workers</h5>
                        <a href="{{route('workers.index')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Dorm tenants</h5>
                        <a href="{{route('dormstudents.index')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>    
            <div class="col-sm-2">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Show invoices</h5>
                            <select id="show_invoices" class="form-control" onchange="show_invoices_function()">
                                <option value="" selected disabled hidden>Select</option>
                                <option value="template">Template</option>
                                <option value="category">Category</option>
                                <option value="dorm">Dorm</option>
                                <option value="room">Room</option>
                                </select>
                        </div>
                    </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
<script>
function invoice_function() 
{

  var x = document.getElementById("invoices").value;
    if(x==='template')
    {
        document.getElementById('invoices').getElementsByTagName('option')[0].selected = 'selected'
        window.location.href = "{{route('template.create')}}"
    }
    else if(x==='category')
    {
        document.getElementById('invoices').getElementsByTagName('option')[0].selected = 'selected'
        window.location.href = "{{route('category.create')}}"
    }
    else if(x==='dorm')
    {
        document.getElementById('invoices').getElementsByTagName('option')[0].selected = 'selected'
        window.location.href = "{{route('dorm.create')}}"
    }
    else if(x==='room')
    {
        document.getElementById('invoices').getElementsByTagName('option')[0].selected = 'selected'
        window.location.href = "{{route('room.create')}}"
    }

}
function show_invoices_function() 
{

  var x = document.getElementById("show_invoices").value;
    if(x==='template')
    {
        document.getElementById('show_invoices').getElementsByTagName('option')[0].selected = 'selected'
        window.location.href = "{{route('template.index')}}"
    }
    else if(x==='category')
    {
        document.getElementById('show_invoices').getElementsByTagName('option')[0].selected = 'selected'
        window.location.href = "{{route('category.index')}}"
    }
    else if(x==='dorm')
    {
        document.getElementById('show_invoices').getElementsByTagName('option')[0].selected = 'selected'
        window.location.href = "{{route('dorm.index')}}"
    }
    else if(x==='room')
    {
        document.getElementById('show_invoices').getElementsByTagName('option')[0].selected = 'selected'
        window.location.href = "{{route('room.index')}}"
    }

}
</script>


