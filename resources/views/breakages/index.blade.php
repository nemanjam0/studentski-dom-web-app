@extends('layouts.app')
@section('content')
<div class="containter">
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">{{ __('Date of report') }}</th>
        <th scope="col">{{ __('Dorm') }}</th>
        <th scope="col">{{ __('Room number') }}</th>
        <th scope="col">{{ __('Description') }}</th>
        <th scope="col">{{ __('Status') }}</th>
        <th scope="col">{{ __('Action') }}</th>
      </tr>
    </thead>
    <tbody>
    @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
    @foreach($breakages as $breakage)
      @if($breakage->status=='answered')
        <tr class="bg-primary">
      @endif
      @if($breakage->status=='solved')
        <tr class="bg-success">
      @endif
      @if($breakage->status=='unsolved')
        <tr class="bg-warning">
      @endif
      @if($breakage->status=='unanswered')
        <tr class="bg-danger">
      @endif
        <th scope="row">{{$i++}}</th>
        <td>{{$breakage->created_at->format('d/m/Y')}}</td>
        <td>{{$breakage->dorm_name}}</td>
        <td>{{$breakage->room_number}}</td>
        <td>{{Str::limit($breakage->description,20)}}</td>
        <td>{{$breakage->status}}</td>
        <td> 
        @if($breakage->status=='unanswered' && Auth::user()->isCraftsman())
        <a href="{{ route('breakages.answer',['id'=>$breakage->id]) }}" class="btn btn-secondary">Answer</a> 
        @endif
        <a href="{{ route('breakages.show',['breakage'=>$breakage->id]) }}" class="btn btn-dark">Show</a> 
        </td>
      
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="d-flex justify-content-center"> {{ $breakages->links() }}</div>
</div>
   
@endsection