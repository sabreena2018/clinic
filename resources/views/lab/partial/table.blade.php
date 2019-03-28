<div class="row mt-4">
    <div class="col">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Appointment Date</th>
                    <th>Time Period</th>
                    <th>@lang('labels.general.actions')</th>
                </tr>

                <tbody>
                @foreach($labs as $lab)
                    <tr>
                        <td>{{ \App\Models\Auth\Lab::find($lab->id)->name }}</td>
                        <td>{{ $lab->appointment }}</td>
                        <td>{{ $lab->Tperiod }}</td>
                        <td>{!! $lab->action_buttons !!}</td>
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