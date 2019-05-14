@extends('backend.layouts.app')


@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         Choose Time
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
                                Time Reservation
                                <small class="text-muted">Choose time for reservation</small>
                            </h4>
                        </div><!--col-->
                    </div><!--row-->

                    <hr>

                    <div class="row mt-4">
                        <div class="col">
                            <div class="row">

                            <div class="col-md-3">
                                <div>Choose Time</div>
                                {!! Form::select('times', $times->pluck('time'), null, ['id' => 'times', 'class' => 'form-control select2_class_doctor']); !!}
                                </div>
                            </div>

                            <br><br>
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="button" id="button_choose" class="btn btn-warning"> Choose </button>
                                </div>
                            </div>


                        </div><!--card-body-->


                        </div><!--col-->
                    </div><!--row-->
                </div><!--card-body-->

                <div class="card-footer">
                    <div class="row">
                        <div class="col">
                            {{ form_cancel(route('admin.registration.show',['type' => (\App\Reservations::find($times[0]['reservation_id']))->type]), __('buttons.general.cancel')) }}
                        </div><!--col-->


                    </div><!--row-->
                </div><!--card-footer-->
            </div><!--card-->


        <script type="application/javascript">

            $(document).ready(function () {
                $('.select2_class_specialties').select2({
                    placeholder: "Select Specialties",
                    tags: true
                });

                let body = $('body');


                body.on('click', '#button_choose', function (e) {
                    e.preventDefault();
                    $.ajax({
                        type: "GET",
                        url: "{{route('admin.reservation.chooseTimeUser')}}?view=true",
                        data: {
                            reservation_id : '{{$times[0]['reservation_id']}}',
                            time : $("#times option:selected").text(),
                            type : '{{(\App\Reservations::find($times[0]['reservation_id']))->type}}',
                        },
                        success: function (result) {
                            window.location.href = "{{route('admin.registration.show',['type' => (\App\Reservations::find($times[0]['reservation_id']))->type])}}";
                            intDeleteButton();
                        },
                        error: function (result) {
                            window.alert("error");
                        }
                    });
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