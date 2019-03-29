@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.access.roles.management'))

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <h4 class="card-title mb-0">
                        Private Doctor Registration
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
                            {{ html()->form('POST', route('admin.private-Doctor.storeReg'))->class('form-horizontal')->open() }}
                            <div  class="card-body">

                                <input type="hidden" id="user_id" name="user_id" value="">

                                <div class="row">
                                    <div class="col-md-3">
                                        <div>Specialties</div>
                                        {!! Form::select('specialties', app(\App\Methods\GeneralMethods::class)->getAllSpecialties(), null, ['id' => 'specialties', 'class' => 'form-control select2_class_service_location']); !!}
                                    </div>

                                    <div class="col-md-3">
                                        <div>Doctor</div>
                                        {!! Form::select('doctor',[],null, ['id' => 'doctor', 'class' => 'form-control select2_class_service_location']); !!}
                                        {{--<select class="form-control select2_class_doctor" id="doctor" name="doctor" >--}}
                                        {{--</select>--}}
                                    </div>




                                    <div class="col-md-3">
                                        <label> Choose appointment</label>
                                        <input class="form-control" id="date" name="date" placeholder="MM-DD-YYY" type="text" autocomplete="off"/>

                                    </div>


                                    <div class="col-md-3">
                                        <label for="Tperiod">Time Period</label>
                                        {!! Form::select('Tperiod', ['' => 'None','morning' => 'Morning', 'evening' => 'Evening'], null, ['id' => 'Tperiod','class' => 'form-control select2_class_service_location']); !!}
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
                            {{ html()->form()->close() }}
                        </div>
                    </div>
                </div>




            </div>
        </div>


        <div class="card">
            <div class="card-body">
                @include('private-doctor.private_doctor_listing_with_filters')
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




                    $('#specialties').change(function(e){
                        $('#doctor').empty();

                        var opt = document.createElement('option');
                        opt.textContent = "Choose Doctor";
                        opt.value = "";
                        var doctor = document.getElementById('doctor');
                        doctor.appendChild(opt);

                        var specialties_id = $('#specialties').val();
                            $.ajax({
                            url: '{{route('admin.private-doctor.getDoctorsDependOnSpecialties')}}',
                            type: "GET",
                            data : {
                                specialties_id : specialties_id,
                            },
                            success:function(data) {
                                $.each(data, function(key, value) {
                                    var first_name = value['first_name'];
                                    var last_name = value['last_name'];
                                    var opt = document.createElement('option');
                                    opt.textContent = first_name + " " +last_name;
                                    opt.value = value['id'];
                                    var doctor = document.getElementById('doctor');
                                    doctor.appendChild(opt);
                                });

                            }
                        });
                    })

                })
            </script>



        {{--<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>--}}
        <!-- Bootstrap Date-Picker Plugin -->
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

    @endpush


@endsection


