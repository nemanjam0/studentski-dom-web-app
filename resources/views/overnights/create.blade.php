@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create room') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('overnights.store') }}">
                        @csrf

                        <div class="form-group row">

                            <label for="person_name" class="col-md-4 col-form-label text-md-right">{{ __('Person name') }}</label>

                            <div class="col-md-6">
                                <input id="person_name" type="text" class="form-control @error('person_name') is-invalid @enderror" name="person_name" value="{{ old('person_name') }}">

                                @error('person_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label for="id_number" class="col-md-4 col-form-label text-md-right">{{ __('ID number') }}</label>

                            <div class="col-md-6">
                                <input id="id_number" type="text" class="form-control @error('id_number') is-invalid @enderror" name="id_number" value="{{ old('id_number') }}">

                                @error('id_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="host" class="col-md-4 col-form-label text-md-right">{{ __('Host username') }}</label>

                            <div class="col-md-6">
                                <input id="host" type="text" class="form-control @error('host') is-invalid @enderror" name="host" value="{{ old('host') }}">

                                @error('host')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                
                        <div class="form-group row">

                            <div class="col-md-4 offset-4">
                            <label for="check_in" >Check-in</label>
                                <input id="check_in" type="date" class="form-control @error('check_in') is-invalid @enderror" name="check_in" value="{{ old('check_in',\Carbon\Carbon::now()->format('Y-m-d')) }}">

                                @error('check_in')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                            <label for="check_out" >Check-out</label>
                                <input id="check_out" type="date" class="form-control @error('check_out') is-invalid @enderror" name="check_out" value="{{ old('check_out',\Carbon\Carbon::now()->format('Y-m-d')) }}">

                                @error('check_out')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            @error('check_in_out')
                            <small id="check_in_out_error" class="form-text text-danger offset-4" >
                                <strong>
                                    {{ $message }}
                                </strong>
                            </small>   
                            @enderror
                            <small id="check_in_out" class="form-text text-muted offset-4">Including check-in/out day.</small>
                         
                        </div>

                        <div class="form-group row">
                          
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create room') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
