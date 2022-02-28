@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning">{{ __('Students loaned items') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('loaneditems.borrow') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="student_name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="student_name" type="text" class="form-control @error('student_name') is-invalid @enderror" name="student_name" value="{{$data['student']->user_name.' '.$data['student']->user_surname}}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dorm" class="col-md-4 col-form-label text-md-right">{{ __('Dorm') }}</label>
                            <div class="col-md-6">
                                <input id="dorm" type="text" class="form-control @error('dorm') is-invalid @enderror" name="dorm" value="{{ $data['student']->dorm_name }}" disabled>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="room_number" class="col-md-4 col-form-label text-md-right">{{ __('Room number') }}</label>
                            <div class="col-md-6">
                                <input id="room_number" type="text" class="form-control @error('room_number') is-invalid @enderror" name="room_number" value="{{ $data['student']->room_number }}" disabled>
                            </div>
                        </div>
                        @if($i=0) @endif
                        @foreach($data['items'] as $item)
                       
                        <div class="form-group row">
                            <label for="item[{{$item->id}}]" class="col-md-4 col-form-label text-md-right">{{$item->name}}</label>
                            
                            <div class="col-md-6">
                                <input id="item[{{$item->id}}]" type="text" class="form-control @error('item.'.$item->id) is-invalid @enderror" name="item[{{$item->id}}]" value="{{ $item->num_of_items }}">
                               
                                @error('item.'.$item->id)
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @if($i++){{-- i++ u if uslovu kako se ne bi ispisivalo na stranici --}} @endif
                        @endforeach
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-dark text-success">{{ __('Hisotry') }}</div>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Item name') }}</th>
                            <th scope="col">{{ __('Quantity') }}</th>
                            <th scope="col">{{ __('Status') }}</th>
                            <th scope="col">{{ __('Borrowed by') }}</th>
                            <th scope="col">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if ($i=1 /*zato sto kad stavim $i=1 ono ga stampa i setuje*/) @endif
                        @foreach($data['loaned_items'] as $loaned_item)
                            @if($loaned_item->quantity>0)
                            <tr class="bg-danger">
                            @else
                            <tr class="bg-primary">
                            @endif
                            
                            <th scope="row">{{$i++}}</th>
                            <td>{{$loaned_item->name}}</td>
                            @if($loaned_item->quantity<"0")
                            <td>{{-$loaned_item->quantity}}</td>
                            <td>Returned</td>
                            @else
                            <td>{{$loaned_item->quantity}}</td>
                            <td>Borrowed</td>
                            @endif
                            <td>{{$loaned_item->borrowed_by_name.' '.$loaned_item->borrowed_by_surname}}</td>
                            <td>{{date('d-m-Y H:i:s', strtotime($loaned_item->created_at))}}</td>
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
