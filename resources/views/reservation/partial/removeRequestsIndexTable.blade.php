<div class="row mt-4">
    <div class="col">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date of Reservation</th>
                </tr>

                <tbody>
                @foreach($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->type }}</td>
                        <td>{{ $reservation->status }}</td>
                        <td>

                            @php

                                if ($reservation->type == 'nurse'){
                                    $nurse = \App\NurseRegistration::query()
                                    ->select('appointmentFrom','appointmentTo')
                                    ->where('reservation_id',$reservation->id)->get()->toArray();

                                    echo $nurse[0]['appointmentFrom'].'   to   '.$nurse[0]['appointmentTo'];
                                }

                                else{
                                    echo $reservation->appointment;
                                }

                            @endphp

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div><!--col-->
</div><!--row-->
<div class="row">
    <div class="col-7">
        <div class="float-left">
            {{ $reservations->total() }} reservations total
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

    body.on('click', '#rejectDestroy', function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "{{route('admin.reservation.destroy')}}",
            data: {
                reservation_id : reservation_id,
                removeStatus : 'remove-rejected',
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


</script>

