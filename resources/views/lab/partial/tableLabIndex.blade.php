<div class="row mt-4">
    <div class="col">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Appointment Date</th>
                    <th>Time Period</th>
                    <th>Time</th>
                    <th>@lang('labels.general.actions')</th>
                </tr>

                <tbody>
                @foreach($labs as $lab)
                    <tr>

                        <td>{{ \App\Models\Auth\Lab::find($lab->lab_id)->name }}</td>
                        <td>{{ $lab->appointment }}</td>
                        <td>{{ $lab->Tperiod }}</td>
                        <td>
                            @php

                            $status = \App\Reservations::find($lab->reservation_id)->status;
                                if ($status == 'confirm-treatment'){
                                        echo badges([$lab->time]);
                                }
                                elseif ($status == 'require-confirm'){
                                        echo badges([$lab->time],'warning');
                                }
                                else{
                                    echo badges([(\App\Reservations::find($lab->reservation_id)->preferred_time)],'danger');
                                }
                            @endphp


                        </td>
                        <td>

                            @php
                                $actionButton = "";
                                $appointment = \App\Reservations::find($lab->reservation_id)->appointment;
                                $status = \App\Reservations::find($lab->reservation_id)->status;
                                $dateOfReservation = \Carbon\Carbon::parse($appointment);
                                $dateNow = \Carbon\Carbon::now();
                                $diff = $dateNow->gte($dateOfReservation);

                                $deleteButton2 = "";
                                $editButton = "";
                                if ($diff != 1 || $status == 'waiting-choose'){
                                 $editButton = '<a href="' . route('admin.reservation.storeTimeUserIndex', ["id" => $lab->reservation_id,'type' => 'lab']) . '" class="btn btn-primary"><i class="fas fa-edit" data-toggle="tooltip" data-placement="top" title="' . __('buttons.general.crud.edit') . '"></i></a>';
                                 $deleteButton2 = '<button type="button" id="destroy" onclick="setResId('.$lab->reservation_id.')" class="btn btn-danger">
                                             <i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="' . __('buttons.general.crud.delete') . '"></i>
                                             </button>';
                                 }

                                    if ($status == 'waiting-choose'){
                                            $actionButton = '<div class="btn-group btn-group-sm" role="group" aria-label="'.__('labels.backend.access.users.user_actions').'">
                                                        '.
                                                        $editButton.$deleteButton2.
                                                        '</div>';

                                    }
                                    elseif($status == 'require-time'){
                                            $actionButton = '<a class="btn btn-warning"><i title="require-time"></i>Waiting</a>';
                                            $actionButton = $actionButton.$deleteButton2;
                                    }
                                    elseif ($status == 'require-confirm'){
                                        $actionButton = '<button type="button" onclick="myFunction('.$lab->reservation_id.')" name="'.$lab->reservation_id.'" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="ConfirmButton">
                                                Confirm reservation
                                            </button>'.$editButton.$deleteButton2;
                                    }
                                    elseif ($status == 'require-confirm-owner'){
                                       $actionButton =  '<a class="btn btn-warning"><i title="require-time"></i>Waiting Clinic Confirm</a>'.$editButton.$deleteButton2;
                                    }
                                elseif($status == 'confirm-treatment'){
                                            $actionButton =  '<a class="btn btn-danger"><i title="require-time"></i>Confirmed</a>'.$editButton.$deleteButton2;
                                    }


                                    $deleteButton = '<a href="' . route('admin.reservation.destroy', $lab->reservation_id) . '"
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
    </div><!--col-->
</div><!--row-->
<div class="row">
    <div class="col-7">
        <div class="float-left">
            {{ $labs->total() }} labs total
        </div>
    </div><!--col-->

    <div class="col-5">
        <div class="float-right">
            {{--                    {!! $lab->render() !!}--}}
        </div>
    </div><!--col-->
</div><!--row-->

<script>

    var reservation_id = 1;

    function myFunction(reservation_idpass) {
        reservation_id = reservation_idpass;
    }

    function setResId(reservation_idpass) {
        reservation_id = reservation_idpass;
    }

    let body = $('body');

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



