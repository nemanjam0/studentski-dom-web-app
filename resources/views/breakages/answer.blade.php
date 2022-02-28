@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Report breakage') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('breakages.update',$data['id']) }}">
                        @method('PUT')
                        @csrf
                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Dorm') }}</label>

                            <div class="col-md-6">
                                <input id="dorm" type="text" class="form-control" name="dorm" value="{{$data['dorm_name']}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Room') }}</label>

                            <div class="col-md-6">
                                <input id="room" type="text" class="form-control" name="room" value="{{$data['room_number']}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Reported by') }}</label>

                            <div class="col-md-6">
                                <input id="dorm" type="text" class="form-control" name="dorm" value="{{$data['reported_by_user_name']}} {{  $data['reported_by_user_surname']}}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" type="textarea" rows="5" cols="50" class="form-control" name="description" readonly>{{$data['description']}}</textarea>

                                @error('report')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                            <div class="form-group row">
                                <label for="answer" class="col-md-4 col-form-label text-md-right">{{ __('Answer') }}</label>

                                    <div class="col-md-6">
                                        <textarea id="answer" type="textardea" rows="5" cols="50" class="form-control @error('answer') is-invalid @enderror" name="answer">{{ old('answer')}}</textarea>

                                        @error('answer')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                            </div>
                            
                        <div class="form-group row">
                            <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>

                            <div class="col-md-6">
                                <select id="status" class="form-control @error('status') is-invalid @enderror" name="status" value="{{ old('status') }}" >
                                
                                <option value="" selected disabled hidden>Select</option>
                                <option value="solved">{{ __('Solved') }}</option>
                                <option value="notsolved">{{ __('Not solved') }}</option>
                                
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Report breakage') }}
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
