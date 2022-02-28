@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Name') }}</th>
      <th scope="col">{{ __('Breakfasts per month') }}</th>
      <th scope="col">{{ __('Lunches per month') }}</th>
      <th scope="col">{{ __('Dinners per month') }}</th>
      <th scope="col">{{ __('Breakfasts per day') }}</th>
      <th scope="col">{{ __('Lunches per day') }}</th>
      <th scope="col">{{ __('Dinners per day') }}</th>
      <th scope="col">{{ __('Breakfast price') }}</th>
      <th scope="col">{{ __('Lunche price') }}</th>
      <th scope="col">{{ __('Dinner price') }}</th>
      <th scope="col">{{ __('Validity days') }}</th>
      <th scope="col">{{ __('Description') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($cards as $card)
    <tr>
      <th scope="row">{{$i++}}</th>
      <td>{{$card->name}}</td>
      <td>@if($card->breakfasts_per_month==null)
        <span class="badge badge-success">28-31</span>
            @else
            {{$card->breakfasts_per_month}}
            @endif
      </td>
      <td>@if($card->lunches_per_month==null)
        <span class="badge badge-success">28-31</span>
            @else
            {{$card->lunches_per_month}}
            @endif
      </td>
      <td>@if($card->dinners_per_month==null)
        <span class="badge badge-success">28-31</span>
            @else
            {{$card->dinners_per_month}}
            @endif
      </td>
      <td>@if($card->breakfasts_per_day==null)
        <span class="badge badge-success">MAX</span>
            @else
            {{$card->breakfasts_per_day}}
            @endif
      </td>
      <td>@if($card->lunches_per_day==null)
        <span class="badge badge-success">MAX</span>
            @else
            {{$card->lunches_per_day}}
            @endif
      </td>
      <td>@if($card->dinners_per_day==null)
        <span class="badge badge-success">MAX</span>
            @else
            {{$card->dinners_per_day}}
            @endif
      </td>
      <td>{{$card->breakfast_price}}</td>
      <td>{{$card->lunch_price}}</td>
      <td>{{$card->dinner_price}}</td>
      <td>{{$card->validity_days}}</td>
      <td>{{$card->description}}</td>
      <td>
      <form method="POST" action="{{ route('cards.destroy',['card'=> $card->id]) }}">
      <a href="{{ route('cards.edit',['card'=>$card->id]) }}" class="btn btn-primary">Edit</a>
      @method('DELETE')
      @csrf
      <!--<button type="submit" class="btn btn-danger">
                                    {{ __('Delete') }}
      </button>-->
      </form>
      </td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
@endsection