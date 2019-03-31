<div class="row mt-4">
    <div class="col">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Doctor</th>
                    {{--<th>Specialties</th>--}}
                    <th>Appointment</th>
                    <th>Time</th>
                    <th>Time Period</th>
                    <th>City</th>
                    <th>Home/Clinic</th>
                    <th>@lang('labels.general.actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($privateDoctors as $privateDoctor)
                    <tr>
                        @php
                         $doctor = \App\Models\Auth\User::find($privateDoctor->doctor_id)
                         @endphp
                        <td>{{ $doctor->first_name }} {{ $doctor->last_name }}</td>
{{--                        <td>{{ \App\Models\Auth\User::find($privateDoctor->doctor_id)->first_name }}</td>--}}
                        <td>{{ \App\Reservations::find($privateDoctor->reservation_id)->appointment }}</td>
                        <td>
                            @php
                                if ((\App\Reservations::find($privateDoctor->reservation_id)->status) == 'approved'){
                                        echo badges([$privateDoctor->time]);
                                }
                                else{
                                    echo badges(['NOT YET'],'danger');
                                }
                            @endphp


                        </td>
                        <td>{{ $privateDoctor->Tperiod }}</td>
                        <td>{{ $privateDoctor->city }}</td>
                        <td>{{ $privateDoctor->serviceL }}</td>
                        <td>
                            @php
                                if ((\App\Reservations::find($privateDoctor->reservation_id)->status) == 'waiting-choose'){
                                        echo $privateDoctor->action_buttons;
                                }
                                elseif((\App\Reservations::find($privateDoctor->reservation_id)->status) == 'require-time'){
                                        echo '<a class="btn btn-warning"><i title="require-time"></i>Waiting</a>';
                                }
                                elseif ((\App\Reservations::find($privateDoctor->reservation_id)->status) == 'approved'){
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
    <div class="col-7">
        <div class="float-left">
            {{--{{ $privateDoctors->total() }} private doctors total--}}
        </div>
    </div><!--col-->

    <div class="col-5">
        <div class="float-right">
            {{--                    {!! $privateDoctor->render() !!}--}}
        </div>
    </div><!--col-->
</div><!--row-->
</div>
