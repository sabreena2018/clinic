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
            {{ html()->form('POST', route('admin.nurse.storeReg'))->class('form-horizontal')->open() }}
            <div  class="card-body">

                <div class="row">
                    <div class="col-md-4">
                        <div>Nurse</div>
                        {!! Form::select('nurseF', app(\App\Methods\GeneralMethods::class)->getAllNurses(), null, ['id' => 'nurseF', 'class' => 'form-control select2_class_doctor','multiple' => 'multiple']); !!}
                    </div>


                    <div class="col-md-4">
                        <label> Choose appointment From</label>
                        <input class="form-control" id="dateFromF" name="dateFromF" placeholder="MM-DD-YYY" type="text" autocomplete="off"/>
                    </div>

                    <div class="col-md-4">
                        <label> To </label>
                        <input class="form-control" id="dateToF" name="dateToF" placeholder="MM-DD-YYY" type="text" autocomplete="off"/>
                    </div>

                    <div class="col-md-3">
                        <div>City</div>
                        {!!  html()->text('city')
                            ->id('cityF')
                            ->class('form-control')
                            ->placeholder('City')
                            ->autofocus()  !!}
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

        $('.load-table').load('{{route('admin.nurse.IndexRegistration')}}?view=true', function () {
            intDeleteButton();
        });


        body.on('click', '#search_btn', function (e) {

            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{route('admin.nurse.IndexRegistration')}}?view=true",
                data: {
                    nurseF: $("#nurseF").val(),
                    dateFromF:$("#dateFromF").val(),
                    dateToF:$("#dateToF").val(),
                    cityF:$("#cityF").val(),
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
</div>
