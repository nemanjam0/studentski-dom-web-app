@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Cardholder') }}</th>
      <th scope="col">{{ __('Type') }}</th>
      <th scope="col">{{ __('Breakfasts') }}</th>
      <th scope="col">{{ __('Lunches') }}</th>
      <th scope="col">{{ __('Dinners') }}</th>
      <th scope="col">{{ __('Money') }}</th>
      <th scope="col">{{ __('Renewed at') }}</th>
      <th scope="col">{{ __('Expires at') }}</th>
      <th scope="col">{{ __('Issued by') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($studentcards as $card)
    <tr>
      <th scope="row">{{$i++}}</th>
      <td>{{$card->cardholder_name}} {{$card->cardholder_surname}}</td>
      <td>{{$card->card_type}}</td>
      <td>{{$card->breakfastsLeft}}</td>
      <td>{{$card->lunchesLeft}}</td>
      <td>{{$card->dinnersLeft}}</td>
      <td>{{$card->moneyLeft}}</td>
      <td>{{$card->renewed}}</td>
      <td>{{$card->expires_at}}</td>
      <td>{{$card->issuer_name}} {{$card->issuer_surname}}</td>
      <td>
      <form method="POST" action="{{ route('studentcards.destroy',['studentcard'=> $card->id]) }}">
      <a href="{{ route('studentcards.edit',['studentcard'=>$card->id]) }}" class="btn btn-primary">Edit/Renew</a>
      @method('DELETE')
      @csrf
      <button type="submit" class="btn btn-danger">
                                    {{ __('Delete') }}
      </button>
      </form>
      </td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
@endsection