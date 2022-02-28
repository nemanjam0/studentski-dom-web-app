@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">{{ __('Return items') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('loaneditems.return') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="student_name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="student_name" type="text" class="form-control @error('student_name') is-invalid @enderror" name="student_name" value="{{$data['student']->user_name.' '.$data['student']->user_surname}}" disabled>
                            </div>
                        </div>
                        <input type="hidden" id="user_id" name="user_id" value="{{$data['student']->user_id}}">
                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Dorm') }}</label>
                            <div class="col-md-6">
                                <input id="dorm" type="text" class="form-control @error('dorm') is-invalid @enderror" name="dorm" value="{{ $data['student']->dorm_name }}" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="room_number" class="col-md-4 col-form-label text-md-right">{{ __('Room number') }}</label>
                            <div class="col-md-6">
                                <input id="room_number" type="text" class="form-control @error('room_number') is-invalid @enderror" name="room_number" value="{{ $data['student']->room_number }}" disabled>
                            </div>
                        </div>
                        @if($i=0) @endif
                        @foreach($data['items'] as $item)
                       
                        <div class="form-group row">
                            <label for="item[{{$item->id}}]" class="col-md-4 col-form-label text-md-right">{{$item->name}}</label>
                            
                            <div class="col-md-6">
                                <input id="item[{{$item->id}}]" type="text" class="form-control @error('item.'.$item->id) is-invalid @enderror" name="item[{{$item->id}}]" value="{{ old('item.'.$item->id,'0') }}">
                               
                                @error('item.'.$item->id)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @if($i++){{-- i++ u if uslovu kako se ne bi ispisivalo na stranici --}} @endif
                        @endforeach
                <!--   <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Student paassword') }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>-->
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Return items') }}
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
