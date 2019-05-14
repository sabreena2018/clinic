<div class="row mt-4">
    <div class="col">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>
                        <div class="form-check form-check-inline mr-1">
                            <input class="form-check-input select_all" type="checkbox" value="on">
                            <label class="form-check-label" for="inline-checkbox1"></label>
                        </div>
                    </th>
                    <th>Name</th>
                    <th>Specialties</th>
                    <th>Doctors</th>
                    <th>Location</th>
                    <th>City</th>
                    <th>Appointment</th>
                    <th>Time</th>
                    <th>Time Period</th>
                    <th>Home / Clinic</th>
                    <th>@lang('labels.general.actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($clinics as $clinic)
                    <tr>
                        <td>
                            <div class="form-check form-check-inline mr-1">
                                <input class="form-check-input row_checkbox" data-id="{{$clinic->id}}" type="checkbox"
                                       value="{{$clinic->id}}">
                            </div>
                        </td>
                        <td>{{ \App\Models\Auth\Clinic::where('id','=',$clinic->clinic_id)->first()->name }}</td>
{{--                        <td>{!! badges($clinic->specialties()->pluck('specialties.name')->toArray()) !!}</td>--}}
                        <td>{{ \App\Models\Auth\Specialties::where('id','=',$clinic->specialties_id)->first()->name  }}</td>
                    {{--<td>    {{\Illuminate\Support\Facades\DB::Table('specialties')->select('name')->where('id',$clinic->specialties_id)->get()}} </td>--}}
                        {{--<td>{!! badges(app(\App\Methods\ClinicMethods::class)->getClinicDoctors($clinic), 'primary')!!}</td>--}}
                        @php
                            $doctor = \App\Models\Auth\User::where('id','=',$clinic->doctor_id)->first()
                        @endphp
                        <td>{{ $doctor->first_name.' '.$doctor->last_name }}</td>
                        <td>{{ $clinic->country }}</td>
                        <td>{{ $clinic->city }}</td>
                        <td>{{ $clinic->appointment }}</td>
                        <td>

                            @php
                                if ((\App\Reservations::find($clinic->reservation_id)->status) == 'confirm-treatment'){
                                        echo badges([$clinic->time]);
                                }
                                else{
                                    echo badges(['NOT YET'],'danger');
                                }
                            @endphp



                        </td>
                        <td>{{ $clinic->Tperiod }}</td>
                        <td>{{ $clinic->serviceL }}</td>
                        {{--<td>{!! $clinic->approved ? badges(['YES']): badges(['NO'], 'danger')!!}</td>--}}
                        <td>




                            @php
                            $actionButton = "";
                                if ((\App\Reservations::find($clinic->reservation_id)->status) == 'waiting-choose'){
                                        $actionButton = $clinic->action_buttons;
                                }
                                elseif((\App\Reservations::find($clinic->reservation_id)->status) == 'require-time'){
                                        $actionButton = '<a class="btn btn-warning"><i title="require-time"></i>Waiting</a>';
                                        $deleteButton2 = '<button type="button" id="destroy" onclick="setResId('.$clinic->reservation_id.')" class="btn btn-danger">
                                         <i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="' . __('buttons.general.crud.delete') . '"></i>
                                         </button>';

                                        $actionButton = $actionButton.$deleteButton2;
                                }
                                elseif ((\App\Reservations::find($clinic->reservation_id)->status) == 'require-confirm'){
                               //     $edit = '<button type="button" id="edit" onclick="setResId('.$clinic->reservation_id.')" class="btn btn-danger"> Edit </button>';
                                    $edit = '<a href="' . route('admin.reservation.storeTimeUserIndex', ["id" => $clinic->reservation_id,'type' => 'clinic']) . '" class="btn btn-primary"><i class="fas fa-edit" data-toggle="tooltip" data-placement="top" title="' . __('buttons.general.crud.edit') . '"></i></a>';
                                    $actionButton = '<button type="button" onclick="myFunction('.$clinic->reservation_id.')" name="'.$clinic->reservation_id.'" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="ConfirmButton">
                                            Confirm reservation
                                        </button>'.$edit;
                                }
                                elseif ((\App\Reservations::find($clinic->reservation_id)->status) == 'require-confirm-owner'){
                                   $actionButton =  '<a class="btn btn-warning"><i title="require-time"></i>Waiting Clinic Confirm</a>';
                                }
                            elseif((\App\Reservations::find($clinic->reservation_id)->status) == 'confirm-treatment'){
                                        $actionButton =  '<a class="btn btn-danger"><i title="require-time"></i>Confirmed</a>';
                                }


                                $deleteButton = '<a href="' . route('admin.reservation.destroy', $clinic->reservation_id) . '"
                                   data-method="delete"
                                   data-trans-button-cancel="' . __('buttons.general.cancel') . '"
                                   data-trans-button-confirm="' . __('buttons.general.crud.delete') . '"
                                   data-trans-title="' . __('strings.backend.general.are_you_sure') . '"
                                   class="btn btn-danger"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="' . __('buttons.general.crud.delete') . '"></i></a> ';

                            echo $actionButton;

                            @endphp
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <!-- Button trigger modal -->


                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to confirm ?</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                You have to Enter your payment information below

                                <form>
                                    <div class="form-group">
                                        <label for="payment_id" class="col-form-label">Visa Card Code :</label>
                                        <input type="text" class="form-control" id="payment_id">
                                    </div>
                                </form>


                            </div>
                            <div class="modal-footer">
                                <button type="button" id="button_close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" id="confirm_payment" class="btn btn-primary">Confirm reservation</button>
                            </div>
                        </div>
                    </div>
                </div>
            </table>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-7">
        <div class="float-left">
            {{ $clinics->total() }} clinics total
        </div>
    </div>

    <div class="col-5">
        <div class="float-right">
        </div>
    </div>
</div>


<script>

    var reservation_id = 1;

    function myFunction(reservation_idpass) {
        reservation_id = reservation_idpass;
    }

    function setResId(reservation_idpass) {
        reservation_id = reservation_idpass;
    }

    {{--body.on('click', '#edit', function (e) {--}}
        {{--e.preventDefault();--}}
        {{--$.ajax({--}}
            {{--type: "get",--}}
            {{--url: "{{route('admin.reservation.storeTimeUserIndex')}}",--}}
            {{--data: {--}}
                {{--id : reservation_id,--}}
                {{--_token: '{{csrf_token()}}',--}}
            {{--},--}}
            {{--success: function (result) {--}}
                {{--// location.reload();--}}
                {{--intDeleteButton();--}}
            {{--},--}}
            {{--error: function (result) {--}}
                {{--window.alert("error");--}}
            {{--}--}}
        {{--});--}}
    {{--});--}}

    body.on('click', '#destroy', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{route('admin.reservation.destroy')}}",
            data: {
                reservation_id : reservation_id,
                _token: '{{csrf_token()}}',
            },
            success: function (result) {
                location.reload();
                intDeleteButton();
            },
            error: function (result) {
                window.alert("error");
            }
        });
    });


    body.on('click', '#confirm_payment', function (e) {
        e.preventDefault();

        if ($('#payment_id').val() != ""){

            $.ajax({
                type: "GET",
                url: "{{route('admin.reservation.confirmReservation')}}?view=true",
                data: {
                    reservation_id : reservation_id,
                },
                success: function (result) {
                    $('#button_close').trigger('click');
                    location.reload();
                    intDeleteButton();
                },
                error: function (result) {
                    window.alert("error");
                }
            });


        }
        else {
            window.alert("require payment information or incorrect information")
        }

        

    });

</script>






