@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.access.roles.management'))

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <h4 class="card-title mb-0">
                        Lab Registration
                    </h4>
                </div>
            </div>
            <br>


            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong>Registration</strong>
                            <small>Form</small>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                {{ html()->form('POST', route('admin.lab.storeReg'))->class('form-horizontal')->open() }}
                                <div  class="card-body">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div>Lab</div>
                                            {!! Form::select('labs', app(\App\Methods\GeneralMethods::class)->getAllLabs(), null, ['id' => 'labs', 'class' => 'form-control select2_class_doctor']); !!}
                                        </div>

                                        <div class="col-md-4">
                                            <label> Choose appointment</label>
                                            <input class="form-control" id="date" name="date" placeholder="MM-DD-YYY" type="text"/>

                                        </div>


                                        <div class="col-md-4">
                                            <label for="Tperiod">Time Period</label>
                                            {!! Form::select('Tperiod', ['' => 'None','morning' => 'Morning', 'evening' => 'Evening'], null, ['id' => 'Tperiod','class' => 'form-control select2_class_service_location']); !!}
                                        </div>



                                    </div>


                                    {{--<button class="btn btn-primary mb-1" type="button" data-toggle="modal" data-target="#clinicModal">Reserve Appointment</button>--}}
                                    <br>
                                    <div class="col text-right">
                                        {{ form_submit(__('buttons.general.crud.create')) }}
                                    </div><!--col-->





                                </div>
                            </div>
                            {{ html()->form()->close() }}
                        </div>
                    </div>
                </div>




            </div>
        </div>


        @push('after-styles')


            <link rel="stylesheet"
                  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

            <link rel="stylesheet"
                  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.3/daterangepicker.min.css">

    @endpush

        @push('after-scripts')


            <script>
                $(document).ready(function(){
                    var date_input=$('input[name="date"]'); //our date input has the name "date"
                    var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                    var options={
                        format: 'yyyy-mm-dd',
                        container: container,
                        todayHighlight: true,
                        autoclose: true,
                    };
                    date_input.datepicker(options);
                })
            </script>



        {{--<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>--}}
        <!-- Bootstrap Date-Picker Plugin -->
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

    @endpush


@endsection


