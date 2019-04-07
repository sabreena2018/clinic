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
                                if ((\App\Reservations::find($nurseReg->reservation_id)->status) == 'waiting-choose'){
                                        echo $nurseReg->action_buttons;
                                }
                                elseif((\App\Reservations::find($nurseReg->reservation_id)->status) == 'require-time'){
                                        echo '<a class="btn btn-warning"><i title="require-time"></i>Waiting</a>';
                                }
                                elseif ((\App\Reservations::find($nurseReg->reservation_id)->status) == 'approved'){
                                    echo '<a class="btn btn-danger"><i title="approved"></i>Approved</a>';
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
            {{--{{ $nurseRegs->total() }} Nurses total--}}
        </div>
    </div><!--col-->

    <div class="col-5">
        <div class="float-right">
            {{--                    {!! $nurse->render() !!}--}}
        </div>
    </div><!--col-->
</div><!--row-->