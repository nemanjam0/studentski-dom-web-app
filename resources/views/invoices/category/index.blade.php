@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Category')}}</th>
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
  @foreach($categoryinvoices as $invoice)
    <tr>   
      <th scope="row">{{$i++}}</th>
      <td>{{$invoice->category}}</td>
      <td>{{$invoice->template_name}}</td>
      <td>{{$invoice->recurring}}</td>
      <td>{{$invoice->amount_of_money}}</td>
      <td>{{$invoice->days_to_pay}}</td>
      <td>{{$invoice->students_finance_status}}</td>
      <td>
      <form method="POST" action="{{ route('category.destroy',['category'=> $invoice->id]) }}">
      <a href="{{ route('category.edit',['category'=>$invoice->id]) }}" class="btn btn-primary">Edit</a>
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