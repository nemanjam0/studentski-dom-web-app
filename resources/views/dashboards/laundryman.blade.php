@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Dashboard</h3>
        <div class="row">
            <div class="col-sm-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Borrowed items </h5>
                        <a href="{{route('loaneditems')}}" class="btn btn-primary">Show</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Borrow items</h5>
                        <form method="POST" action="{{ route('laundryman.student_borrowing') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="">{{ __('Students e-mail') }}</label>

                                <input id="email" type="text" class="form-control @error('email','student_borrowing') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email','student_borrowing')
                                    <span class="invalid-feedback bg-dark" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Borrow items') }}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                 
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Return items</h5>
                        <form method="POST" action="{{ route('laundryman.student_returning') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="">{{ __('Students e-mail') }}</label>

                                <input id="email" type="text" class="form-control @error('email','student_returning') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email','student_returning')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Return items') }}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Student's items</h5>
                        <form method="POST" action="{{ route('laundryman.student_items') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email" class="">{{ __('Students e-mail') }}</label>

                                <input id="email" type="text" class="form-control @error('email','student_items') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email','student_items')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                           
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Show') }}
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            
          
            <!--
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
