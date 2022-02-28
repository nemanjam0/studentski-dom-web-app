@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Name') }}</th>
      <th scope="col">{{ __('Recurring') }}</th>
      <th scope="col">{{ __('Day of the week') }}</th>
      <th scope="col">{{ __('Day') }}</th>
      <th scope="col">{{ __('Month') }}</th>
      <th scope="col">{{ __('Amount of money') }}</th>
      <th scope="col">{{ __('Days to pay') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($templates as $template)
    <tr>   
      <th scope="row">{{$i++}}</th>
      <td>{{$template->name}}</td>
      <td>{{$template->recurring}}</td>
      <td>
        @if($template->day_of_the_week==0)
        <span class="badge badge-warning">X</span>
            @else
            {{ $template->day_of_the_week}}
            @endif
        </td>
        
        <td>
            @if($template->day==0)
            <span class="badge badge-warning">X</span>
                @else
                {{ $template->day}}
                @endif
        </td>
        <td>
            @if($template->month==0)
            <span class="badge badge-warning">X</span>
                @else
                {{ $template->month}}
                @endif
        </td>
      <td>{{$template->amount_of_money}}</td>
      <td>{{$template->days_to_pay}}</td>
      <td>
      <form method="POST" action="{{ route('template.destroy',['template'=> $template->id]) }}">
      <a href="{{ route('template.edit',['template'=>$template->id]) }}" class="btn btn-primary">Edit</a>
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