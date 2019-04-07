<div class="row mt-4">
    <div class="col">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date of Reservation</th>
                    <th>@lang('labels.general.actions')</th>
                </tr>

                <tbody>
                @foreach($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->type }}</td>
                        <td>{{ $reservation->status }}</td>
                        <td>{{ $reservation->appointment }}</td>
                        {{--<td>{!! $reservation->action_buttons !!}</td>--}}
                        <td>
                            @php

                                if ($reservation->status == "confirmed"){
                                echo '<a class="btn btn-danger"><i title="require-time"></i>Confirmed</a>';
                                }
                               else{
                                  echo $reservation->action_buttons;
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