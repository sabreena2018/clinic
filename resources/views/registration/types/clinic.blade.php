@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.access.roles.management'))

@section('content')






    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <h4 class="card-title mb-0">
                        Clinic Registration
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

                                {{--<div class="form-group col-md-6">--}}
                                    {{--<label for="phone">Choose Clinic</label>--}}
                                    {{--{!! Form::select('patientClinics[]', [], null, ['id' => 'patientClinics','class' => 'form-control select2_class_clinics_for_patient', 'multiple' => 'multiple']); !!}--}}
                                    {{--<small>You can choose multiple clinics, An appointment will be made to you at the--}}
                                        {{--first clinic that approves your application--}}
                                    {{--</small>--}}
                                {{--</div>--}}


                                {{--<div class="form-group col-md-6">--}}
                                    {{--<label for="phone">Choose Specialties</label>--}}
                                    {{--{!! Form::select('patientSpecialties[]', [], null, ['id' => 'patientSpecialties','class' => 'form-control select2_class_specialties_for_patient']); !!}--}}
                                    {{--<small>Not required</small>--}}
                                {{--</div>--}}

                                {{--<div class="form-group col-md-6">--}}
                                    {{--<label for="phone">Choose Doctors</label>--}}
                                    {{--{!! Form::select('patientDoctors[]', [], null, ['id' => 'patientDoctors','class' => 'form-control select2_class_doctors_for_patient']); !!}--}}
                                    {{--<small>Not required</small>--}}
                                {{--</div>--}}
                                {{ html()->form('POST', route('admin.clinic.storeClinicUser'))->class('form-horizontal')->open() }}
                                <div  class="card-body">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div>Doctors</div>
                                            {!! Form::select('doctors', app(\App\Methods\GeneralMethods::class)->getAllDoctors(), null, ['id' => 'doctors', 'class' => 'form-control select2_class_doctor']); !!}
                                        </div>

                                        <div class="col-md-3">
                                            <div>Clinics</div>
                                            {!! Form::select('clinics', app(\App\Methods\GeneralMethods::class)->getAllClinics(), null, ['id' => 'clinics','class' => 'form-control select2_class_clinic']); !!}
                                        </div>

                                        <div class="col-md-3">
                                            <div>Specialties</div>
                                            {!! Form::select('specialties', app(\App\Methods\GeneralMethods::class)->getAllSpecialties(), null, ['id' => 'specialties','class' => 'form-control select2_class_specialties']); !!}
                                        </div>

                                        <div class="col-md-3">
                                            <div>Location</div>
                                            {!! Form::select('countries', app(\App\Methods\GeneralMethods::class)->getAllCountries(), null, ['id' => 'countries','class' => 'form-control select2_class_countries']); !!}
                                        </div>


                                        <div class="col-md-3">
                                            <div>City</div>
                                            {!!  html()->text('city')
                                                ->id('city')
                                                ->class('form-control')
                                                ->placeholder('City')
                                                ->autofocus()  !!}
                                        </div>

                                        <div class="col-md-3">
                                            <label> Choose appointment</label>
                                            <input class="form-control" id="date" name="date" placeholder="MM/DD/YYY" type="text" autocomplete="off"/>

                                        </div>


                                        <div class="col-md-3">
                                            <label for="Tperiod">Time Period</label>
                                            {!! Form::select('Tperiod', ['' => 'None','morning' => 'Morning', 'evening' => 'Evening'], null, ['id' => 'Tperiod','class' => 'form-control select2_class_Tperiod']); !!}
                                        </div>


                                        <div class="col-md-3">
                                            <label for="phone">Home / clinic</label>
                                            {!! Form::select('service_location', ['' => 'None','home' => 'Home', 'clinic' => 'Clinic'], null, ['id' => 'service_location','class' => 'form-control select2_class_service_location']); !!}


                                        </div>

                                    </div>


                                {{--<button class="btn btn-primary mb-1" type="button" data-toggle="modal" data-target="#clinicModal">Reserve Appointment</button>--}}
                                    <br>
                                    <div class="row">
                                    <div class="col text-right">
                                            {{ form_submit(__('buttons.general.crud.create')) }}
                                        </div><!--col-->
                                    </div>





                            </div>
                        </div>
                            {{ html()->form()->close() }}
                    </div>
                </div>
            </div>




        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @include('clinic.clinic_listing_with_filters')
        </div>
    </div>

    <script type="application/javascript">

        let body = $('body');

        $(document).ready(function () {
            $('.select2_class_service_location').select2({
                placeholder: "Select Service Location",
            });
        });

    </script>


@endsection

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


    <script type="text/javascript">

        $('.select2_class_clinics_for_patient').select2({
            placeholder: "Select Clinic",
        });

        $('.select2_class_specialties_for_patient').select2({
            placeholder: "Select Specialties",
        });

        $('.select2_class_doctors_for_patient').select2({
            placeholder: "Select Doctors",
        });


        $('.select2_class_Tperiod').select2({
            placeholder: "Select Time period",
        });

        $(function () {
            $('#datetimepicker12').datetimepicker({
                inline: true,
                sideBySide: true,
            });
        });

        $('#patientClinics').on('select2:select', function (e) {
            var clinicsIds = $(this).val();

            $('#patientSpecialties').select2({
                ajax: {
                    url: '/api/clinics/specialties',
                    data: {
                        'clinicsIds': clinicsIds
                    }
                }
            });

        });


        $('#patientSpecialties').on('select2:select', function (e) {
            var specialtiesId = $(this).val();

            $('#patientDoctors').select2({
                ajax: {
                    url: '/api/specialties/doctors',
                    data: {
                        'specialtiesId': specialtiesId
                    }
                }
            });

        });

    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.3/daterangepicker.min.js"></script>
@endpush