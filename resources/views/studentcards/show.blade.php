@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success">{{ __('Student card') }}</div>

                <div class="card-body">
                    <form>
                        <div class="form-group row">
                            <label for="id" class="col-md-4 col-form-label text-md-right">{{ __('Card number') }}</label>

                            <div class="col-md-6">
                                <input id="id" type="text" class="form-control @error('id') is-invalid @enderror" name="id" value="{{$studentcard->id}}" disabled>

                                @error('id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        </div>

                        <div class="form-group row">
                            <label for="cardholder" class="col-md-4 col-form-label text-md-right">{{ __('Cardholder') }}</label>


                            <div class="col-md-6">
                                <input id="cardholder" type="text" class="form-control @error('cardholder') is-invalid @enderror" name="cardholder" value="{{ $studentcard->cardholder_name}} {{ $studentcard->cardholder_surname}}" disabled>

                                @error('cardholder')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label for="card_type" class="col-md-4 col-form-label text-md-right">{{ __('Card type') }}</label>


                            <div class="col-md-6">
                                <input id="card_type" type="text" class="form-control @error('card_type') is-invalid @enderror" name="card_type" value="{{ $studentcard->card_type}}" disabled>

                                @error('card_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="issuer" class="col-md-4 col-form-label text-md-right">{{ __('Issuer') }}</label>


                            <div class="col-md-6">
                                <input id="issuer" type="text" class="form-control @error('issuer') is-invalid @enderror" name="issuer" value="{{ $studentcard->issuer_name }} {{ $studentcard->issuer_surname }}" disabled>

                                @error('issuer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="renewed_at" class="col-md-4 col-form-label text-md-right">{{ __('Room Description') }}</label>


                            <div class="col-md-6">
                                <input id="renewed_at" type="text" class="form-control @error('renewed_at') is-invalid @enderror" name="renewed_at" value="{{$studentcard->renewed_at}}" disabled>

                                @error('renewed_at')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="expires_at" class="col-md-4 col-form-label text-md-right">{{ __('Expires at') }}</label>


                            <div class="col-md-6">
                                <input id="expires_at" type="text" class="form-control @error('expires_at') is-invalid @enderror" name="expires_at" value="{{$studentcard->expires_at}}" disabled>

                                @error('expires_at')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="breakfastsLeft" class="col-md-4 col-form-label text-md-right">{{ __('Breakfasts left') }}</label>

                            <div class="col-md-6">
                                <input id="breakfastsLeft" type="text" class="form-control @error('breakfastsLeft') is-invalid @enderror" name="breakfastsLeft" value="{{$studentcard->breakfastsLeft}}" disabled>
                                @error('breakfastsLeft')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lunchesLeft" class="col-md-4 col-form-label text-md-right">{{ __('Lunches left') }}</label>

                            <div class="col-md-6">
                                <input id="lunchesLeft" type="text" class="form-control @error('lunchesLeft') is-invalid @enderror" name="lunchesLeft" value="{{$studentcard->lunchesLeft}}" disabled>
                                @error('breakfastsLeft')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dinnersLeft" class="col-md-4 col-form-label text-md-right">{{ __('Dinners left') }}</label>

                            <div class="col-md-6">
                                <input id="dinnersLeft" type="text" class="form-control @error('dinnersLeft') is-invalid @enderror" name="dinnersLeft" value="{{$studentcard->dinnersLeft}}" disabled>
                                @error('dinnersLeft')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="money" class="col-md-4 col-form-label text-md-right">{{ __('Money') }}</label>

                            <div class="col-md-6">
                                <input id="money" type="text" class="form-control @error('money') is-invalid @enderror" name="money" value="{{$studentcard->moneyLeft}}" disabled>
                                @error('money')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        </br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success">{{ __('Card transactions') }}</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Breakfasts') }}</th>
                            <th scope="col">{{ __('Lunches') }}</th>
                            <th scope="col">{{ __('Dinners') }}</th>
                            <th scope="col">{{ __('Money') }}</th>
                            <th scope="col">{{ __('By') }}</th>
                            <th scope="col">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
                        @foreach($transactions as $transaction)
                            <tr>
                                <th scope="row">{{$i++}}</th>
                                <td>{{$transaction->breakfasts_change}}</td>
                                <td>{{$transaction->lunches_change}}</td>
                                <td>{{$transaction->dinners_change}}</td>
                                <td>{{$transaction->money_change}}</td>
                                <td>{{$transaction->executed_name}} {{$transaction->executed_surname}}</td>
                                <td>{{$transaction->created_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
