@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Report breakage') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('breakages.store') }}">
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
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea id="description" rows="5" cols="50" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description')}}</textarea>

                                @error('description')
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
