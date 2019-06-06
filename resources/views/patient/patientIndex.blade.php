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
                                        <th>Name</th>
                                        <th>@lang('labels.general.actions')</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($patients as $patient)
                                    <tr>
                                        <td>
                                            {{ $patient->name }}
                                        </td>
                                        <td>
                                            @php
                                                echo '<a href="'.  route('admin.patient.PatientReservationRecord', $patient->id) .'" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.view').'" class="btn btn-info"><i class="fas fa-eye"></i></a>';
                                            @endphp
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

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.patient.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
@endsection
