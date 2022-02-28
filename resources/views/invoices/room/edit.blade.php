@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create new dorm invoice') }}</div>

                <div class="card-body">
                <form method="POST" action="{{ route('room.update',['room'=>$roomtemplate->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="room_number" class="col-md-4 col-form-label text-md-right">{{ __('Room number') }}</label>

                            <div class="col-md-6">
                                <input id="room_number" type="text" class="form-control @error('room_number') is-invalid @enderror" name="room_number" value="{{ old('room_number',$roomtemplate->room_number) }}" required autocomplete="room_number" autofocus>

                                @error('room_number')
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
                                @foreach($dorms as $dorm)
                                <option value="{{$dorm->id}}" @if(($dorm->id)==old('dorm',$roomtemplate->dorm_id)) selected="selected" @endif>{{$dorm->name}} ({{$dorm->address}})</option>
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
                            <label for="template" class="col-md-4 col-form-label text-md-right">{{ __('Template') }}</label>

                            <div class="col-md-6">
                                <select id="template" class="form-control @error('template') is-invalid @enderror" name="template" value="{{ old('template') }}" >
                                @foreach($templates as $template)
                                <option value="{{$template->id}}" @if(($template->id)==old('template',$roomtemplate->invoice_template_id)) selected="selected" @endif>{{$template->name}} ({{$template->recurring}}) {{$template->amount_of_money}} RSD</option>
                                @endforeach
                                </select>
                                @error('template')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="students_finance_status" class="col-md-4 col-form-label text-md-right">{{ __('Student financing stauts') }}</label>

                            <div class="col-md-6">
                                <select id="students_finance_status" class="form-control @error('students_finance_status') is-invalid @enderror" name="students_finance_status" value="{{ old('students_finance_status') }}" >
                                <option value="budget"@if('budget'== old('students_finance_status',$roomtemplate->students_finance_status)) selected="selected" @endif>Budget</option>
                                <option value="self-financing" @if('self-financing'==old('students_finance_status',$roomtemplate->students_finance_status)) selected="selected" @endif>Self-financing</option>
                                <option value="all_types" @if('all_types'==old('students_finance_status',$roomtemplate->students_finance_status)) selected="selected" @endif>All types</option>
                                </select>
                                @error('students_finance_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
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
