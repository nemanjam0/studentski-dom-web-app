@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Dorm') }}</th>
      <th scope="col">{{ __('Room number') }}</th>
      <th scope="col">{{ __('Invoice template')}}</th>
      <th scope="col">{{ __('Recurring') }}</th>
      <th scope="col">{{ __('Amout of money') }}</th>
      <th scope="col">{{ __('Days to pay') }}</th>
      <th scope="col">{{ __('Students finance status') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($roominvoices as $invoice)
    <tr>   
      <th scope="row">{{$i++}}</th>
      <td>{{$invoice->dorm_name}}</td>
      <td>{{$invoice->room_number}}</td>
      <td>{{$invoice->template_name}}</td>
      <td>{{$invoice->recurring}}</td>
      <td>{{$invoice->amount_of_money}}</td>
      <td>{{$invoice->days_to_pay}}</td>
      <td>{{$invoice->students_finance_status}}</td>
      <td>
      <form method="POST" action="{{ route('room.destroy',['room'=> $invoice->id]) }}">
      <a href="{{ route('room.edit',['room'=>$invoice->id]) }}" class="btn btn-primary">Edit</a>
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