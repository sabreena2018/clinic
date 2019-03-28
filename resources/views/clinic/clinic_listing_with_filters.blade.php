<div class="card">
    <h5 class="card-header">
        Filter
        {!! Form::submit('Search', ['id' => 'search_btn', 'class' => 'btn btn-primary float_right']); !!}

        <button class="btn btn-primary float_right" type="button" data-toggle="collapse"
                href="#collapsePanel" aria-expanded="false" aria-controls="collapsePanel">
            Collapse
        </button>
    </h5>
    <div class="card-body">
    <div id="collapsePanel" class="collapse multi-collapse">

        <div class="row">
            <div class="col-md-3">
                <div>Doctors</div>
                {!! Form::select('doctorsF[]', app(\App\Methods\GeneralMethods::class)->getAllDoctors(), null, ['id' => 'doctorsF', 'class' => 'form-control select2_class_doctor', 'multiple' => 'multiple']); !!}
            </div>

            <div class="col-md-3">
                <div>Clinics</div>
                {!! Form::select('clinicsF[]', app(\App\Methods\GeneralMethods::class)->getAllClinics(), null, ['id' => 'clinicsF','class' => 'form-control select2_class_clinic', 'multiple' => 'multiple']); !!}
            </div>

            <div class="col-md-3">
                <div>Specialties</div>
                {!! Form::select('specialtiesF[]', app(\App\Methods\GeneralMethods::class)->getAllSpecialties(), null, ['id' => 'specialtiesF','class' => 'form-control select2_class_specialties', 'multiple' => 'multiple']); !!}
            </div>

            <div class="col-md-3">
                <div>Countries</div>
                {!! Form::select('countriesF[]', app(\App\Methods\GeneralMethods::class)->getAllCountries(), null, ['id' => 'countriesF','class' => 'form-control select2_class_countries', 'multiple' => 'multiple']); !!}
            </div>


            <div class="col-md-3">
                <div>City</div>
                {!!  html()->text('city')
                    ->id('cityFwe')
                    ->class('form-control')
                    ->placeholder('City')
                    ->autofocus()  !!}
            </div>

            <div class="col-md-3">
                <label> Choose appointment</label>
                <input class="form-control" id="dateF" name="dateF" placeholder="MM/DD/YYY" type="text" autocomplete="off"/>

                <script>
                    $(document).ready(function(){
                        var date_input=$('input[name="dateF"]'); //our date input has the name "date"
                        var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
                        var options={
                            format: 'yyyy-mm-dd',
                            container: container,
                            todayHighlight: true,
                            autoclose: true,
                        };
                        date_input.datepicker(options);
                    })
                </script>

            </div>

            <div class="col-md-3">
                <label for="Tperiod">Time Period</label>
                {!! Form::select('TperiodF', ['' => 'None','morning' => 'Morning', 'evening' => 'Evening'], null, ['id' => 'TperiodF','class' => 'form-control select2_class_Tperiod']); !!}
            </div>


            <div class="col-md-3">
                <label for="phone">Service Location</label>
                {!! Form::select('service_locationF', ['' => 'None','home' => 'Home', 'clinic' => 'Clinic'], null, ['id' => 'service_locationF','class' => 'form-control select2_class_service_location']); !!}

            </div>

        </div>

    </div>
    </div>
</div>

<div class="load-table">

</div>

<script type="application/javascript">


    $(document).ready(function () {

        let body = $('body');


        body.on('click', '.select_all', function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });

        $('.select2_class_doctor').select2({
            placeholder: "Select Doctor",
        });

        $('.select2_class_clinic').select2({
            placeholder: "Select Clinic",
        });

        $('.select2_class_specialties').select2({
            placeholder: "Select Specialties",
        });

        $('.select2_class_countries').select2({
            placeholder: "Select Countries",
        });

        $('.load-table').load('{{route('admin.clinic.indexUserClinic')}}?view=true', function () {
            intDeleteButton();
        });


        body.on('click', '#search_btn', function (e) {

            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{route('admin.clinic.indexUserClinic')}}?view=true",
                data: {
                    doctors: $("#doctorsF").val(),
                    clinics: $("#clinicsF").val(),
                    specialties: $("#specialtiesF").val(),
                    countries: $("#countriesF").val(),
                    city: $("#cityFwe").val(),
                    date: $("#dateF").val(),
                    Tperiod: $("#TperiodF").val(),
                    Home_clinic: $("#service_locationF").val(),
                },
                success: function (result) {
                    $('.load-table').html(result);
                    intDeleteButton();
                },
                error: function (result) {
                }
            });

        });


        body.on('click', '.change_status_button', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            $.ajax({
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                url: url,
                success: function (result) {
                    $('#search_btn').click();
                },
                error: function (result) {
                }
            });

        });

    });


</script>


<style>
    .float_right {
        float: right;
        margin-left: 3px;
    }

    .select2-container {
        width: 100% !important;
    }

</style>
