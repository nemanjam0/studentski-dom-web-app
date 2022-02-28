@extends('layouts.app')
@section('content')
<div class="containter">
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">{{ __('Name') }}</th>
      <th scope="col">{{ __('Description') }}</th>
      <th scope="col">{{ __('Action') }}</th>
    </tr>
  </thead>
  <tbody>
  @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
  @foreach($items as $item)
    <tr>
      <th scope="row">{{$i++}}</th>
      <td>{{$item->name}}</td>
      <td>{{$item->description}}</td>
      <td>
      <form method="POST" action="{{ route('items.destroy',['item'=> $item->id]) }}">
      <a href="{{ route('items.edit',['item'=>$item->id]) }}" class="btn btn-primary">Edit</a>
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