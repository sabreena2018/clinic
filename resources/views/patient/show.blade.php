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
                        {{ html()->label(__('validation.attributes.backend.access.roles.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{$patient->name ?? '-'}}
                        </div><!--col-->
                    </div>


                    <div class="form-group row">
                        {{ html()->label('Email')
                            ->class('col-md-2 form-control-label')
                            ->for('email') }}

                        <div class="col-md-10">
                            {{$patient->email ?? '-'}}
                        </div><!--col-->
                    </div>


                    <div class="form-group row">
                        {{ html()->label('Clinics')
                            ->class('col-md-2 form-control-label')
                            ->for('email') }}

                        <div class="col-md-10">
                            {!! badges($patient->clinics()->pluck('name')->toArray()) !!}
                        </div><!--col-->
                    </div>



                    <div class="form-group row">
                        {{ html()->label('Phone Number')
                        ->class('col-md-2 form-control-label')
                        ->for('phone') }}

                        <div class="col-md-10">
                            {!! $patient->phone ?? '-' !!}
                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        {{ html()->label('City')
                        ->class('col-md-2 form-control-label')
                        ->for('city') }}

                        <div class="col-md-10">
                            {!! $patient->city ?? '-' !!}
                        </div><!--col-->
                    </div>


                </div><!--col-->


            </div><!--row-->





            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right" style="float: right;">
                        {!! $patient->action_buttons !!}
                    </div><!--col-->
                </div>

            </div>


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
                                        <th>Type</th>
                                        <th>Time</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($reservations as $reservation)
                                    <tr>
                                        <td>

                                        </td>

                                        <td>
                                            {{ $reservation->type }}
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
