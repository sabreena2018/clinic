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
                                Clinic Management
                                <small class="text-muted">Create Clinic</small>
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
                                    {{ html()->text('Times')
                                        ->class('form-control')
                                        ->placeholder('Time')
                                        ->id('time')
                                        ->autofocus() }}
                                </div><!--col-->





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


        <script type="application/javascript">
            var listitems = [];

            $(document).ready(function () {
                $('.select2_class_specialties').select2({
                    placeholder: "Select Specialties",
                    tags: true
                });

                let body = $('body');

                body.on('click', '#button_insert', function (e) {
                    var node = document.createElement("LI");
                    var time = document.getElementById('time');
                    var textnode = document.createTextNode(time.value);
                    node.setAttribute('class','list-group-item active');
                    node.appendChild(textnode);
                    document.getElementById("mylist").appendChild(node);
                    listitems.push(time.value);
                    time.value = "";

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


        @push('after-styles')



            <style>
                .select2-container {
                    width: 100% !important;
                }
            </style>
    @endpush