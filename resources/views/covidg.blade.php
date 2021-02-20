@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Submit') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ action ('ClinicController@covidgenerate') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="date1" class="col-md-4 col-form-label text-md-right">{{ __('Date1') }}</label>

                            <div class="col-md-6">
                                <input id="date1" type="text" class="form-control" name="date1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name1" class="col-md-4 col-form-label text-md-right">{{ __('Name1') }}</label>

                            <div class="col-md-6">
                                <input id="name1" type="text" class="form-control" name="name1">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="license_no" class="col-md-4 col-form-label text-md-right">{{ __('License No.') }}</label>

                            <div class="col-md-6">
                                <input id="license_no" type="text" class="form-control" name="license_no">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name2" class="col-md-4 col-form-label text-md-right">{{ __('Name2') }}</label>

                            <div class="col-md-6">
                                <input id="name2" type="text" class="form-control" name="name2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date2" class="col-md-4 col-form-label text-md-right">{{ __('Date2') }}</label>

                            <div class="col-md-6">
                                <input id="date2" type="text" class="form-control" name="date2">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name3" class="col-md-4 col-form-label text-md-right">{{ __('Name3') }}</label>

                            <div class="col-md-6">
                                <input id="name3" type="text" class="form-control" name="name3">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="date3" class="col-md-4 col-form-label text-md-right">{{ __('Date3') }}</label>

                            <div class="col-md-6">
                                <input id="date3" type="text" class="form-control" name="date3">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name4" class="col-md-4 col-form-label text-md-right">{{ __('Name4') }}</label>

                            <div class="col-md-6">
                                <input id="name4" type="text" class="form-control" name="name4">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name5" class="col-md-4 col-form-label text-md-right">{{ __('Name5') }}</label>

                            <div class="col-md-6">
                                <input id="name5" type="text" class="form-control" name="name5">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address1" class="col-md-4 col-form-label text-md-right">{{ __('Address1') }}</label>

                            <div class="col-md-6">
                                <input id="address1" type="text" class="form-control" name="address1">
                            </div>
                        </div>
                     

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
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
