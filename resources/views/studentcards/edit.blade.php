@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit/renew student card') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('studentcards.update',['studentcard'=>$studentcard->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">

                            <label for="cardholder" class="col-md-4 col-form-label text-md-right">{{ __('Cardholder') }}</label>

                            <div class="col-md-6">
                                <input id="cardholder" type="text" class="form-control @error('cardholder') is-invalid @enderror" name="cardholder" value="{{ $studentcard->cardholder_name }} {{ $studentcard->cardholder_surname}}" disabled>

                                @error('cardholder')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="card" class="col-md-4 col-form-label text-md-right">{{ __('Card') }}</label>

                            <div class="col-md-6">
                                <select id="card" class="form-control @error('card') is-invalid @enderror" name="card" value="{{ old('card') }}" >
                                @foreach($cards as $card)
                                <option value="{{$card->id}}">{{$card->name}}</option>
                                @endforeach
                                </select>
                                @error('card')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                        
                        <div class="form-group row">
                            <div class="col-md-6  offset-md-4">
                            @if(isset($renewing))
                                <input id="renew" type="checkbox" class="form-check-input @error('renew') is-invalid @enderror" name="renew" checked disabled>
                                <input type="hidden" id="renew" name="renew" value="1">
                            @else
                            <input id="renew" type="checkbox" class="form-check-input @error('renew') is-invalid @enderror" name="renew" {{ old('renew') == 'on' ? 'checked' : '' }}>
                            @endif
                                <label class="form-check-label" for="renew">{{ __('Renew') }}</label>

                                @error('renew')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit/renew') }}
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
