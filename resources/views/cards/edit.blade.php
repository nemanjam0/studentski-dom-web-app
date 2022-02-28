@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit card type') }}</div>

                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                    If number of meals per month is not specified that means that card have meals for every day in month.
                    <br>
                    If number of meals per day is not specified that means that cardholder can use as much meals per day as he wants(upper limit is num. of meals per month)
                    <br>
                    If card vadility is not specified that means that card does not expire.
                    </div>
                    <form method="POST" action="{{ route('cards.update',['card'=>$card->id]) }}">
                        @method('PUT')
                        @csrf

                        <div class="form-group row">

                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name',$card->name) }}">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">

                            <label for="breakfasts_per_month" class="col-md-4 col-form-label text-md-right">{{ __('Breakfasts per month') }}</label>

                            <div class="col-md-6">
                                <input id="breakfasts_per_month" type="text" class="form-control @error('breakfasts_per_month') is-invalid @enderror" name="breakfasts_per_month" value="{{ old('breakfasts_per_month',$card->breakfasts_per_month) }}">

                                @error('breakfasts_per_month')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
      
                        <div class="form-group row">

                            <label for="lunches_per_month" class="col-md-4 col-form-label text-md-right">{{ __('Lunches per month') }}</label>

                            <div class="col-md-6">
                                <input id="lunches_per_month" type="text" class="form-control @error('lunches_per_month') is-invalid @enderror" name="lunches_per_month" value="{{ old('lunches_per_month',$card->lunches_per_month) }}">

                                @error('lunches_per_month')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                              
                        <div class="form-group row">

                            <label for="dinners_per_month" class="col-md-4 col-form-label text-md-right">{{ __('Dinners per month') }}</label>

                            <div class="col-md-6">
                                <input id="dinners_per_month" type="text" class="form-control @error('dinners_per_month') is-invalid @enderror" name="dinners_per_month" value="{{ old('dinners_per_month',$card->dinners_per_month) }}">

                                @error('dinners_per_month')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                              
                        <div class="form-group row">

                            <label for="breakfasts_per_day" class="col-md-4 col-form-label text-md-right">{{ __('Breakfasts per day') }}</label>

                            <div class="col-md-6">
                                <input id="breakfasts_per_day" type="text" class="form-control @error('breakfasts_per_day') is-invalid @enderror" name="breakfasts_per_day" value="{{ old('breakfasts_per_day',$card->breakfasts_per_day) }}">

                                @error('breakfasts_per_day')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                              
                        <div class="form-group row">

                            <label for="lunches_per_day" class="col-md-4 col-form-label text-md-right">{{ __('Lunches per day') }}</label>

                            <div class="col-md-6">
                                <input id="lunches_per_day" type="text" class="form-control @error('lunches_per_day') is-invalid @enderror" name="lunches_per_day" value="{{ old('lunches_per_day',$card->lunches_per_day) }}">

                                @error('lunches_per_day')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                              
                        <div class="form-group row">

                            <label for="dinners_per_day" class="col-md-4 col-form-label text-md-right">{{ __('Dinners per day') }}</label>

                            <div class="col-md-6">
                                <input id="dinners_per_day" type="text" class="form-control @error('dinners_per_day') is-invalid @enderror" name="dinners_per_day" value="{{ old('dinners_per_day',$card->dinners_per_day) }}">

                                @error('dinners_per_day')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="breakfast_price" class="col-md-4 col-form-label text-md-right">{{ __('Breakfast price') }}</label>

                            <div class="col-md-6">
                                <input id="breakfast_price" type="text" class="form-control @error('breakfast_price') is-invalid @enderror" name="breakfast_price" value="{{ old('breakfast_price',$card->breakfast_price) }}">

                                @error('breakfast_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                            
                        <div class="form-group row">

                            <label for="lunch_price" class="col-md-4 col-form-label text-md-right">{{ __('Lunch price') }}</label>

                            <div class="col-md-6">
                                <input id="lunch_price" type="text" class="form-control @error('lunch_price') is-invalid @enderror" name="lunch_price" value="{{ old('lunch_price',$card->lunch_price) }}">

                                @error('lunch_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                            
                        <div class="form-group row">

                            <label for="dinner_price" class="col-md-4 col-form-label text-md-right">{{ __('Dinner price') }}</label>

                            <div class="col-md-6">
                                <input id="dinner_price" type="text" class="form-control @error('dinner_price') is-invalid @enderror" name="dinner_price" value="{{ old('dinner_price',$card->dinner_price) }}">

                                @error('dinner_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">

                            <label for="validity_days" class="col-md-4 col-form-label text-md-right">{{ __('Card validity (in days)') }}</label>

                            <div class="col-md-6">
                                <input id="validity_days" type="text" class="form-control @error('validity_days') is-invalid @enderror" name="validity_days" value="{{ old('validity_days',$card->validity_days) }}">

                                @error('validity_days')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                            <div class="col-md-6">
                                <textarea rows="5" id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description',$card->description) }}</textarea>
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
                                    {{ __('Create') }}
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
