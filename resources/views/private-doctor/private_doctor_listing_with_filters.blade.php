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

        <div class="card-body">
            <div  class="card-body">


                <div class="row">
                    <div class="col-md-3">
                        <div>Specialties</div>
                        {!! Form::select('specialtiesF', app(\App\Methods\GeneralMethods::class)->getAllSpecialties(), null, ['id' => 'specialtiesF', 'class' => 'form-control select2_class_service_location']); !!}
                    </div>


                    <div class="col-md-3">
                        <div>Doctor</div>
                        {!! Form::select('doctorF',[],null, ['id' => 'doctorF', 'class' => 'form-control select2_class_service_location']); !!}
                    </div>




                    <div class="col-md-3">
                        <label> Choose appointment</label>
                        <input class="form-control" id="dateF" name="dateF" placeholder="MM-DD-YYY" type="text" autocomplete="off"/>

                    </div>


                    <div class="col-md-3">
                        <label for="Tperiod">Time Period</label>
                        {!! Form::select('TperiodF', ['' => 'None','morning' => 'Morning', 'evening' => 'Evening'], null, ['id' => 'TperiodF','class' => 'form-control select2_class_service_location']); !!}
                    </div>

                    <div class="col-md-3">
                        <div>City</div>
                        {!!  html()->text('city')
                            ->id('cityF')
                            ->class('form-control')
                            ->placeholder('City')
                            ->autofocus()  !!}
                    </div>

                    <div class="col-md-3">
                        <label for="phone">Home / clinic</label>
                        {!! Form::select('service_locationF', ['' => 'None','home' => 'Home', 'clinic' => 'Clinic'], null, ['id' => 'service_locationF','class' => 'form-control select2_class_service_location']); !!}

                    </div>




                </div>

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

        $('.load-table').load('{{route('admin.private-doctor.indexRegistration')}}?view=true', function () {
            intDeleteButton();
        });


        body.on('click', '#search_btn', function (e) {

            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{route('admin.private-doctor.indexRegistration')}}?view=true",
                data: {
                    specialtiesF: $("#specialtiesF").val(),
                    doctorF:$("#doctorF").val(),
                    dateF:$("#dateF").val(),
                    TperiodF:$("#TperiodF").val(),
                    cityF:$("#cityF").val(),
                    service_locationF:$("#service_locationF").val(),
                },
                success: function (result) {
                    $('.load-table').html(result);
                    intDeleteButton();
                },
                error: function (result) {
                }
            });

        });

        $('#specialtiesF').change(function(e){
            $('#doctorF').empty();

            var opt = document.createElement('option');
            opt.textContent = "Choose Doctor";
            opt.value = "";
            var doctor = document.getElementById('doctorF');
            doctor.appendChild(opt);

            var specialties_id = $('#specialtiesF').val();
            $.ajax({
                url: '{{route('admin.private-doctor.getDoctorsDependOnSpecialties')}}',
                type: "GET",
                data : {
                    specialties_id : specialties_id,
                },
                success:function(data) {
                    $.each(data, function(key, value) {
                        var first_name = value['first_name'];
                        var last_name = value['last_name'];
                        var opt = document.createElement('option');
                        opt.textContent = first_name + " " +last_name;
                        opt.value = value['id'];
                        var doctor = document.getElementById('doctorF');
                        doctor.appendChild(opt);
                    });

                }
            });
        })


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
