@extends('layouts.app')
@section('content')
<div class="containter">
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">{{ __('Name') }}</th>
        <th scope="col">{{ __('College') }}</th>
        <th scope="col">{{ __('Year') }}</th>
        <th scope="col">{{ __('Dorm (Room)') }}</th>
        <th scope="col">{{ __('Action') }}</th>
      </tr>
    </thead>
    <tbody>
    @if ($i=1 /*zato sto kad stavim $i=1 ono ga i STAMPA i setuje*/) @endif
    @foreach($students as $student)
      <tr>
        <th scope="row">{{$i++}}</th>
        <td>{{$student->user->name}} {{$student->user->surname}}</td>
        <td>{{$student->college->name}}</td>
        <td>{{$student->year_of_study}}</td>
        <td>@if($student->dorm_id==null)
        <span class="badge badge-success">No</span>
            @else
            {{$student->dorm->name." (".$student->room->room_number.')'}}
            @endif
        </td>
        <td>
        <form method="POST" action="{{ route('students.destroy',['student'=> $student->id]) }}">
        <a href="{{ route('students.edit',['student'=>$student->id]) }}" class="btn btn-primary">Edit</a>
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
  <div class="d-flex justify-content-center"> {{ $students->links() }}</div>
</div>
   
@endsection