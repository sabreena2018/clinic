@extends('backend.layouts.app')


@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        Patient Management
                        <small class="text-muted">Show Patient</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->
            <!--row-->

            <hr/>


            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label('Patient Record')
                        ->class('col-md-2 form-control-label')
                        ->for('patientRecord') }}

                        <div class="col-md-10">

                            <div class="table-responsive">
                                <table class="table">

                                    <thead>
                                    <tr>
                                        <th>Id of Reservation</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Appointment</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($patientRecords as $patientRecord)
                                    <tr>
                                        {!! logger($patientRecord) !!}
                                        <td>
                                            {{ $patientRecord["id"] }}
                                        </td>
                                        <td>
                                            {{ $patientRecord["name"] }}
                                        </td>

                                        <td>
                                            {{ $patientRecord["type"] }}
                                        </td>

                                        <td>
                                            {{ $patientRecord["appointment"] }}
                                        </td>

                                        <td>
                                            {{ $patientRecord["created_at"] }}
                                        </td>

                                        <td>
                                            {{ $patientRecord["status"] }}
                                        </td>

                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>



                        </div><!--col-->
                    </div>

                </div><!--col-->


            </div><!--row-->


        </div><!--card-body-->

    </div><!--card-->
@endsection
