@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $student->student_name}}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="middle_name" class="col-md-4 col-form-label text-md-right">{{ __('Middle name') }}</label>

                            <div class="col-md-6">
                                <input id="middle_name" type="text" class="form-control" name="middle_name" value="{{ $student->middle_name}}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>

                            <div class="col-md-6">
                                <input id="surname" type="text" class="form-control" name="surname" value="{{ $student->surname}}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $student->email}}"disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="birthday_date" class="col-md-4 col-form-label text-md-right">{{ __('Birthday') }}</label>

                            <div class="col-md-6">
                                <input id="birthday_date" type="date" class="form-control" name="birthday_date" value="{{ $student->birthday_date}}" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="college" class="col-md-4 col-form-label text-md-right">{{ __('College') }}</label>

                            <div class="col-md-6">
                            <input id="college" type="text" class="form-control" name="college" value="{{ $student->college_name}}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="year_of_study" class="col-md-4 col-form-label text-md-right">{{ __('Year of study') }}</label>

                            <div class="col-md-6">
                                <input id="year_of_study" type="text" class="form-control" name="year_of_study" value="{{$student->year_of_study}}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="br_indeksa" class="col-md-4 col-form-label text-md-right">{{ __('Broj indeksa') }}</label>

                            <div class="col-md-6">
                                <input id="br_indeksa" type="text" class="form-control" name="br_indeksa" value="{{ $student->indeks}}" disabled>
                            </div>
                        </div>
          
                        <div class="form-group row">
                            <label for="finance_status" class="col-md-4 col-form-label text-md-right">{{ __('Finance status') }}</label>

                            <div class="col-md-6">
                            <input id="finance_status" type="text" class="form-control" name="finance_status" value="{{ $student->finance_status}}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Dorm') }}</label>

                            <div class="col-md-6">
                            <input id="dorm" type="text" class="form-control" name="dorm" value="{{ $student->dorm_name}}" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="room_number" class="col-md-4 col-form-label text-md-right">{{ __('Room number') }}</label>

                            <div class="col-md-6">
                            <input id="room_number" type="text" class="form-control" name="room_number" value="{{ $student->room_number}}" disabled>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection