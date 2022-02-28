@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add new student') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('students.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="middle_name" class="col-md-4 col-form-label text-md-right">{{ __('Middle name') }}</label>

                            <div class="col-md-6">
                                <input id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" name="middle_name" value="{{ old('middle_name') }}">

                                @error('middle_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}">

                                @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="birthday_date" class="col-md-4 col-form-label text-md-right">{{ __('Birthday') }}</label>

                            <div class="col-md-6">
                                <input id="birthday_date" type="date" class="form-control @error('birthday_date') is-invalid @enderror" name="birthday_date" value="{{ old('birthday_date','2000-01-01') }}">

                                @error('birthday_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="college" class="col-md-4 col-form-label text-md-right">{{ __('College') }}</label>

                            <div class="col-md-6">
                                <select id="college" class="form-control @error('college') is-invalid @enderror" name="college" value="{{ old('college') }}" >
                                @foreach($colleges as $college)
                                <option value="{{$college->id}}">{{$college->name}} ({{$college->address}})</option>
                                @endforeach
                                </select>
                                @error('college')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="year_of_study" class="col-md-4 col-form-label text-md-right">{{ __('Year of study') }}</label>

                            <div class="col-md-6">
                                <input id="year_of_study" type="text" class="form-control @error('year_of_study') is-invalid @enderror" name="year_of_study" value="{{ old('year_of_study') }}">

                                @error('year_of_study')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="br_indeksa" class="col-md-4 col-form-label text-md-right">{{ __('Broj indeksa') }}</label>

                            <div class="col-md-6">
                                <input id="br_indeksa" type="text" class="form-control @error('br_indeksa') is-invalid @enderror" name="br_indeksa" value="{{ old('br_indeksa') }}">

                                @error('br_indeksa')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                      

                        <div class="form-group row">
                            <label for="finance_status" class="col-md-4 col-form-label text-md-right">{{ __('Finance status') }}</label>

                            <div class="col-md-6">
                                <select id="finance_status" class="form-control @error('finance_status') is-invalid @enderror" name="finance_status" value="{{ old('finance_status') }}" >
                                
                                <option value="budget">{{ __('budget') }}</option>
                                <option value="self-financing">{{ __('self-financing') }}</option>
                                
                                </select>
                                @error('finance_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Make') }}
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