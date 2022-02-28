@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Add worker to dorm') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('workers.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="user_id" class="col-md-4 col-form-label text-md-right">{{ __('Worker') }}</label>

                            <div class="col-md-6">
                                <select id="user_id" class="form-control @error('user_id') is-invalid @enderror" name="user_id" value="{{ old('user_id') }}" >
                                @foreach($data['workers'] as $worker)
                                <option value="{{$worker->id}}">{{$worker->name}} {{$worker->surname}}</option>
                                @endforeach
                                </select>
                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dorm_id" class="col-md-4 col-form-label text-md-right">{{ __('Dorm') }}</label>

                        <div class="col-md-6">
                                <select id="dorm_id" class="form-control @error('dorm_id') is-invalid @enderror" name="dorm_id" value="{{ old('dorm_id') }}" >
                                @foreach($data['dorms'] as $dorm)
                                <option value="{{$dorm->id}}">{{$dorm->name}} ({{$dorm->address}})</option>
                                @endforeach
                                </select>
                                @error('dorm_id')
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