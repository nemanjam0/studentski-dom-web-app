@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Student name') }}</th>
      <th scope="col">{{ __('Dorm') }}</th>
      <th scope="col">{{ __('Room') }}</th>
      <th scope="col">{{ __('Borrowed by') }}</th>
      <th scope="col">{{ __('Item name') }}</th>
      <th scope="col">{{ __('Quantity') }}</th>
      <th scope="col">{{ __('Status') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($loaned_items as $loaned_item)
    @if($loaned_item->quantity>0)
    <tr class="bg-danger">
    @else
    <tr class="bg-primary">
    @endif
    
      <th scope="row">{{$i++}}</th>
     <td> <a href="{{ route('loaneditems.studentsitems',['user_id'=> $loaned_item->borrower_user_id]) }}" class="text-dark">{{$loaned_item->borrower_name.' '.$loaned_item->borrower_surname}}</a></td>
      <td>{{$loaned_item->dorm_name}}</td>
      <td>{{$loaned_item->room_number}}</td>
      <td>{{$loaned_item->borrowed_by_name.' '.$loaned_item->borrowed_by_surname}}</td>
      <td>{{$loaned_item->item_name}}</td>
      @if($loaned_item->quantity<"0")
      <td>{{-$loaned_item->quantity}}</td>
      <td>Returned</td>
      @else
      <td>{{$loaned_item->quantity}}</td>
      <td>Borrowed</td>
      @endif
    </tr>
    @endforeach
  </tbody>
  </table>
  <div class="d-flex justify-content-center"> {{ $loaned_items->links() }}</div>
</div>
@endsection