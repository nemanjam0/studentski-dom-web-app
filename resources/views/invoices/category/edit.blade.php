@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create new dorm invoice') }}</div>

                <div class="card-body">
                <form method="POST" action="{{ route('category.update',['category'=>$categoryinvoice->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group row">
                            <label for="category" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                            <div class="col-md-6">
                                <input id="category" type="text" class="form-control @error('category') is-invalid @enderror" name="category" value="{{ old('category',$categoryinvoice->category) }}" required autocomplete="category" autofocus>

                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="template" class="col-md-4 col-form-label text-md-right">{{ __('Template') }}</label>

                            <div class="col-md-6">
                                <select id="template" class="form-control @error('template') is-invalid @enderror" name="template" value="{{ old('template') }}" >
                                @foreach($templates as $template)
                                <option value="{{$template->id}}" @if(($template->id)==old('template',$categoryinvoice->invoice_template_id)) selected="selected" @endif>{{$template->name}} ({{$template->recurring}}) {{$template->amount_of_money}} RSD</option>
                                @endforeach
                                </select>
                                @error('template')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="students_finance_status" class="col-md-4 col-form-label text-md-right">{{ __('Student financing stauts') }}</label>

                            <div class="col-md-6">
                                <select id="students_finance_status" class="form-control @error('students_finance_status') is-invalid @enderror" name="students_finance_status" value="{{ old('students_finance_status') }}" >
                                <option value="budget"@if('budget'== old('students_finance_status',$categoryinvoice->students_finance_status)) selected="selected" @endif>Budget</option>
                                <option value="self-financing" @if('self-financing'==old('students_finance_status',$categoryinvoice->students_finance_status)) selected="selected" @endif>Self-financing</option>
                                <option value="all_types" @if('all_types'==old('students_finance_status',$categoryinvoice->students_finance_status)) selected="selected" @endif>All types</option>
                                </select>
                                @error('students_finance_status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create room') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
