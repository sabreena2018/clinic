<div class="row mt-4">
    <div class="col">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Nurse Name</th>
                    <th>Appointment From </th>
                    <th>Appointment To</th>
                    <th>City</th>
                    <th>@lang('labels.general.actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($nurseRegs as $nurseReg)
                    <tr>
                        @php
                            $nurse = \App\Models\Auth\User::find($nurseReg->nurse_id)
                        @endphp
                        <td>{{ $nurse->first_name }} {{ $nurse->last_name }}</td>
                        <td>{{ $nurseReg->appointmentFrom }} </td>
                        <td>{{ $nurseReg->appointmentTo }} </td>
                        <td>{{ $nurseReg->city }} </td>
                        <td>
                            @php
                            $actionButton = "";
                            $status = \App\Reservations::find($nurseReg->reservation_id)->status;
                            $appointment = \App\Reservations::find($nurseReg->reservation_id)->appointment;

                            $dateOfReservation = \Carbon\Carbon::parse($appointment);
                            $dateNow = \Carbon\Carbon::now();
                            $diff = $dateNow->gte($dateOfReservation);

                            $deleteButton2 = "";
                            $editButton = "";
                            if ($diff != 1 || $status == 'waiting-choose'){
                             $deleteButton2 = '<button type="button" id="destroy" onclick="setResId('.$nurseReg->reservation_id.')" class="btn btn-danger">
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
                                    $actionButton = '<button type="button" onclick="myFunction('.$nurseReg->reservation_id.')" name="'.$nurseReg->reservation_id.'" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="ConfirmButton">
                                            Confirm reservation
                                        </button>'.$editButton.$deleteButton2;
                                }
                                elseif ($status == 'require-confirm-owner'){
                                   $actionButton =  '<a class="btn btn-warning"><i title="require-time"></i>Waiting Clinic Confirm</a>'.$editButton.$deleteButton2;
                                }
                            elseif($status == 'confirm-treatment'){
                                        $actionButton =  '<a class="btn btn-danger"><i title="require-time"></i>Confirmed</a>'.$editButton.$deleteButton2;
                                }

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
            {{--{{ $nurseRegs->total() }} Nurses total--}}
        </div>
    </div><!--col-->

    <div class="col-5">
        <div class="float-right">
            {{--                    {!! $nurse->render() !!}--}}
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

