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

        {{--<div class="row">--}}
            {{--<div class="col-md-3">--}}
                {{--<label for="lab">Lab</label>--}}
                {{--@php--}}
                {{--$arr = array();--}}
                {{--$arr = app(\App\Methods\GeneralMethods::class)->getAllLabs();--}}
                {{--array_unshift($arr , 'None');--}}
                {{--dd($arr);--}}
                {{--@endphp--}}
{{--                {!! $arr = app(\App\Methods\GeneralMethods::class)->getAllLabs() !!}--}}
                {{--{!! Form::select('labF', $arr, null, ['id' => 'labF','class' => 'form-control select2_class_Tperiod']); !!}--}}
                    {{--{!! Form::select('labF', app(\App\Methods\GeneralMethods::class)->getAllLabs(), null, ['id' => 'labF', 'class' => 'form-control select2_class_doctor', 'multiple' => 'multiple']); !!}--}}
            {{--</div>--}}

            {{--<div class="col-md-3">--}}
                {{--<label> Choose appointment</label>--}}
                {{--<input class="form-control" id="dateF" name="dateF" placeholder="MM/DD/YYY" type="text" autocomplete="off"/>--}}

                {{--<script>--}}
                    {{--$(document).ready(function(){--}}
                        {{--var date_input=$('input[name="dateF"]'); //our date input has the name "date"--}}
                        {{--var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";--}}
                        {{--var options={--}}
                            {{--format: 'yyyy-mm-dd',--}}
                            {{--container: container,--}}
                            {{--todayHighlight: true,--}}
                            {{--autoclose: true,--}}
                        {{--};--}}
                        {{--date_input.datepicker(options);--}}
                    {{--})--}}
                {{--</script>--}}

            {{--</div>--}}

            {{--<div class="col-md-3">--}}
                {{--<label for="Tperiod">Time Period</label>--}}
                {{--{!! Form::select('TperiodF', ['' => 'None','morning' => 'Morning', 'evening' => 'Evening'], null, ['id' => 'TperiodF','class' => 'form-control select2_class_Tperiod']); !!}--}}
            {{--</div>--}}



        {{--</div>--}}

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

        $('.load-table').load('{{route('admin.lab.index')}}?view=true', function () {
            intDeleteButton();
        });


        body.on('click', '#search_btn', function (e) {

            e.preventDefault();
            $.ajax({
                type: "GET",
                url: "{{route('admin.lab.index')}}?view=true",
                data: {
                    lab: $("#labF").val(),
                    date: $("#dateF").val(),
                    Tperiod: $("#TperiodF").val(),
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
