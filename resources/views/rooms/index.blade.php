@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table table-sm">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Dorm') }}</th>
      <th scope="col">{{ __('Room number') }}</th>
      <th scope="col">{{ __('Category') }}</th>
      <th scope="col">{{ __('Tenants/Capacity') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($rooms as $room)
    @if($room->students_in_room==$room->room_capacity)
    <tr class="bg-danger">
    @elseif($room->students_in_room==0)
    <tr class="bg-success">
    @else
    <tr class="bg-info">
    @endif
    
      <th scope="row">{{$i++}}</th>
      <td>{{$room->dorm_name}}</td>
      <td>{{$room->room_number}}</td>
      @if($room->category!=0)
      <td>{{$room->category}}</td>
      @else
      <td><span class="badge badge-success">DORM CATEGORY</span></td>
      @endif
      <td>{{$room->students_in_room}}/{{$room->room_capacity}}</td>
      <td>
      <form method="POST" action="{{ route('rooms.destroy',['room'=> $room->room_id]) }}">
      <a href="{{ route('rooms.show',['room'=>$room->room_id]) }}" class="btn btn-warning">Show</a>
      <a href="{{ route('rooms.edit',['room'=>$room->room_id]) }}" class="btn btn-primary">Edit</a>
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
</div>
</table>
@endsection