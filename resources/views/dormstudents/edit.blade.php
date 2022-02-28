@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit student(dorm tenant)') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('dormstudents.update',['dormstudent'=>$data['dormstudent']->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="user_name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>


                            <div class="col-md-6">
                                <input id="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ $data['dormstudent']->user_name}}" readonly>

                                @error('user_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="user_surname" class="col-md-4 col-form-label text-md-right">{{ __('Surname') }}</label>


                            <div class="col-md-6">
                                <input id="user_surname" type="text" class="form-control @error('user_surname') is-invalid @enderror" name="user_surname" value="{{ $data['dormstudent']->user_surname}}" readonly>

                                @error('user_surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                      

                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Dorm') }}</label>

                            <div class="col-md-6">
                                <select id="dorm" class="form-control @error('dorm') is-invalid @enderror" name="dorm" value="{{ old('dorm') }}" >
                                @foreach($data['dorms'] as $dorm)
                                <option value="{{$dorm->id}}" @if(($dorm->id)==$data['dormstudent']->dorm_id) selected="selected" @endif>{{$dorm->name}}</option>
                                @endforeach
                                </select>
                                @error('dorm')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="room_number" class="col-md-4 col-form-label text-md-right">{{ __('Room number') }}</label>


                            <div class="col-md-6">
                                <input id="room_number" type="text" class="form-control @error('room_number') is-invalid @enderror" name="room_number" value="{{ $data['dormstudent']->room_number}}">

                                @error('room_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit') }}
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
