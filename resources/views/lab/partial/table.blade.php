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
                                if ((\App\Reservations::find($lab->reservation_id)->status) == 'approved'){
                                        echo badges([$lab->time]);
                                }
                                else{
                                    echo badges(['NOT YET'],'danger');
                                }
                            @endphp


                        </td>
                        <td>
                            @php
                                if ((\App\Reservations::find($lab->reservation_id)->status) == 'waiting-choose'){
                                        echo $lab->action_buttons;
                                }
                                elseif((\App\Reservations::find($lab->reservation_id)->status) == 'require-time'){
                                        echo '<a class="btn btn-warning"><i title="require-time"></i>Waiting</a>';
                                }
                                elseif ((\App\Reservations::find($lab->reservation_id)->status) == 'approved'){
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
            {{ $labs->total() }} labs total
        </div>
    </div><!--col-->

    <div class="col-5">
        <div class="float-right">
            {{--                    {!! $lab->render() !!}--}}
        </div>
    </div><!--col-->
</div><!--row-->



