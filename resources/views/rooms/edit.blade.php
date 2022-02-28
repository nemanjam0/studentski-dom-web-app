@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success">{{ __('Edit room') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('rooms.update',['room'=>$data['room']->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Dorm') }}</label>

                            <div class="col-md-6">
                            <input id="dorm" type="text" class="form-control @error('dorm') is-invalid @enderror" name="dorm" value="{{ $data['dorm']->name}} ({{ $data['dorm']->address}})" disabled>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="room_number" class="col-md-4 col-form-label text-md-right">{{ __('Room number') }}</label>


                            <div class="col-md-6">
                                <input id="room_number" type="text" class="form-control @error('room_number') is-invalid @enderror" name="room_number" value="{{ $data['room']->room_number}}">

                                @error('room_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label for="room_capacity" class="col-md-4 col-form-label text-md-right">{{ __('Room capacity') }}</label>


                            <div class="col-md-6">
                                <input id="room_capacity" type="text" class="form-control @error('room_capacity') is-invalid @enderror" name="room_capacity" value="{{ $data['room']->room_capacity}}">

                                @error('room_capacity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="floor" class="col-md-4 col-form-label text-md-right">{{ __('Floor') }}</label>


                            <div class="col-md-6">
                                <input id="floor" type="text" class="form-control @error('floor') is-invalid @enderror" name="floor" value="{{ $data['room']->floor }}">

                                @error('floor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="room_description" class="col-md-4 col-form-label text-md-right">{{ __('Room Description') }}</label>


                            <div class="col-md-6">
                                <input id="room_description" type="text" class="form-control @error('room_description') is-invalid @enderror" name="room_description" value="{{$data['room']->description}}">

                                @error('room_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="category" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                            <div class="col-md-6">
                                <input id="category" type="text" class="form-control @error('category') is-invalid @enderror" name="category" value=@if($data['room']->category!=0) {{ old('category',$data['room']->category) }} @endif>
                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="alert alert-info" role="alert">
                            If you do not set room category room will inherit category from dorm.
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit room') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success">{{ __('Students in room') }}</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Surname') }}</th>
                            <th scope="col">{{ __('College') }}</th>
                            <th scope="col">{{ __('Year') }}</th>
                            <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
                        @foreach($data['students'] as $student)
                            <tr>
                                <th scope="row">{{$i++}}</th>
                                <td>{{$student->student_name}}</td>
                                <td>{{$student->student_surname}}</td>
                                <td>{{$student->college_name}}</td>
                                <td>{{$student->year}}</td>
                                <td>
                                    <form method="POST" action="{{ route('dormstudents.destroy',['dormstudent'=> $student->student_id]) }}">
                                    <a href="{{ route('students.edit',['student'=> $student->student_id]) }}" class="btn btn-primary">Edit</a>
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                                                    {{ __('Move out') }}
                                    </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
