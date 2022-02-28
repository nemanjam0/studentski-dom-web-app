@extends('layouts.app')
@section('content')
<div class="containter">
@if($errors->any())
<div role="alert" class="alert alert-danger">
     {{$errors->first()}}
    </div> 
@endif
    <div role="alert" class="alert alert-info">
        Money: {{$moneyLeft}}
    </div> 
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Name') }}</th>
      <th scope="col">{{ __('Amount of money')}}</th>
      <th scope="col">{{ __('Date of issue') }}</th>
      <th scope="col">{{ __('Due date') }}</th>
      <th scope="col">{{ __('Paid') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($bills as $bill)
    <tr>   
      <th scope="row">{{$i++}}</th>
      <td>{{$bill->name}}</td>
      <td>{{$bill->amount_of_money}}</td>
      <td>{{$bill->date_of_issue}}</td>
      <td>{{$bill->due_date}}</td>
      <td>@if($bill->paid==0)
        <span class="badge badge-danger">No</span>
            @else
            <span class="badge badge-success">Yes</span>
            @endif
      </td>
      <td>
      @if($bill->paid==false)
        @if($bill->amount_of_money<=$moneyLeft)
        <a href="{{ route('userbills.edit',['userbill'=>$bill->id]) }}" class="btn btn-primary">Pay</a>
        @else
        <span class="badge badge-danger">You need {{$bill->amount_of_money-$moneyLeft}} RSD</span>
        @endif
        @else
        <span class="badge badge-success">Paid</span>
      @endif
      </td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
<div class="d-flex justify-content-center"> {{ $bills->links() }}</div>

@endsection