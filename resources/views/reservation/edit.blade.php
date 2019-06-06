@extends('backend.layouts.app')


@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ $reservation->type }} Reservation
                        <br>
                        <small class="text-muted">Insert available time</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->
            <!--row-->

            <hr/>


            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <h4 class="card-title mb-0">
                                {{ $reservation->type }} Management
                                <small class="text-muted">Create {{ $reservation->type }}</small>
                            </h4>
                        </div><!--col-->
                    </div><!--row-->

                    <hr>

                    <div class="row mt-4">
                        <div class="col">


                            <div class="row">
                                <div class="col-md-4">
                                    {{--<label> Patient Name: {{ \App\Models\Auth\User::find((\App\LabRegistration::find($reservation->id)->user_id))->name }}</label>--}}
                                    <label> Patient Name: {{ \App\Models\Auth\User::find($reservation->user_id)->name }}</label>

                                </div><!--col-->
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label> Appointment Date: {{ (\App\Reservations::find($reservation->id)->appointment)}}</label>
                                </div><!--col-->
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label> Preferred Time: {{ (\App\Reservations::find($reservation->id)->preferred_time)}}</label>
                                </div><!--col-->
                            </div>



                            <div class="row">
                                {{--<div class="col-md-4">--}}
                                    {{--{{ html()->text('Times')--}}
                                        {{--->class('form-control')--}}
                                        {{--->placeholder('Time')--}}
                                        {{--->id('time')--}}
                                        {{--->autofocus() }}--}}
                                {{--</div>--}}

                                <div class="col-md-4">
                                    <input class="form-control" id="time" name="time" placeholder="HH:MM" type="text" autocomplete="off"/>
                                </div>



                                <div class="col-md-3">
                                    <button type="button" id="button_insert" class="btn btn-warning">Insert >> </button>
                                </div>


                                <div class="col-md-3">
                                    <ul class="list-group" id="mylist">

                                    </ul>
                                </div><!--col-->
                            </div><!--row-->

                            <br><br><br>


                        </div><!--card-body-->


                    </div><!--col-->
                </div><!--row-->
            </div><!--card-body-->



            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ $reservation->type }} Appointments
                    </h4>
                </div><!--col-->
            </div>


            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">


                        <div class="col-md-4">

                            <div class="table-responsive">
                                <table class="table">

                                    <thead>
                                    <tr>
                                        <th style='text-align:center'>Appointments Date</th>
                                        <th style='text-align:center'>Appointments Time</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($appointments as $appointment)
                                        <tr>
{{--                                            {!! logger($patientRecord) !!}--}}
                                            <td style='text-align:center'>
                                                {{ $appointment->appointment }}
                                            </td>

                                            <td style='text-align:center'>
                                                {{ $appointment->time }}
                                            </td>

{{--                                            <td>--}}
{{--                                                {{ $patientRecord["name"] }}--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                {{ $patientRecord["type"] }}--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                {{ $patientRecord["appointment"] }}--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                {{ $patientRecord["created_at"] }}--}}
{{--                                            </td>--}}

{{--                                            <td>--}}
{{--                                                {{ $patientRecord["status"] }}--}}
{{--                                            </td>--}}

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>



                        </div><!--col-->
                    </div>

                </div><!--col-->


            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.reservation.index'), __('buttons.general.cancel')) }}
                    </div><!--col-->

                    <div class="col text-right">
                        <button type="button" id="button_done" class="btn btn-warning">Done</button>
                    </div><!--col-->
                </div><!--row-->
            </div><!--card-footer-->
        </div><!--card-->

        @push('after-styles')


            <link rel="stylesheet"
                  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

            <link rel="stylesheet"
                  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.3/daterangepicker.min.css">

        @endpush

        @push('after-scripts')

            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.3/daterangepicker.min.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>


        @endpush


        <script type="application/javascript">
            var listitems = [];

            $(document).ready(function () {



                $('#time').datetimepicker({
                    format: 'LT'
                });


                $('.select2_class_specialties').select2({
                    placeholder: "Select Specialties",
                    tags: true
                });

                let body = $('body');

                body.on('click', '#button_insert', function (e) {
                    var timeFormat = /^(1[0-2]|0?[1-9]):([0-5]?[0-9])(.?[AP]M)?$/;
                    var time = document.getElementById('time');
                    if (time.value === ""){
                        window.alert("You must enter time");
                    }
                    else if(timeFormat.test(time.value) == false){
                        window.alert("Time must be in this formate HH:MM | Example : 08:40");
                    }
                    else{
                    var node = document.createElement("LI");
                    var textnode = document.createTextNode(time.value);
                    node.setAttribute('class','list-group-item active');
                    node.appendChild(textnode);
                    document.getElementById("mylist").appendChild(node);
                    listitems.push(time.value);
                    time.value = "";
                    }
                });





                body.on('click', '#button_done', function (e) {
                    if( $('#mylist li').length >= 1 ){
                        e.preventDefault();
                        $.ajax({
                            type: "GET",
                            url: "{{route('admin.reservation.storeItems')}}?view=true",
                            data: {
                                reservation_id : '{{ $reservation->id}}',
                                listitem : listitems,
                            },
                            success: function (result) {
                                window.location.href = "{{route('admin.reservation.index')}}";
                                intDeleteButton();
                            },
                            error: function (result) {
                                window.alert("error");
                            }
                        });

                    }
                });




            });
        </script>




        @endsection


        @push('after-styles')1
            <style>
                .select2-container {
                    width: 100% !important;
                }
            </style>
    @endpush