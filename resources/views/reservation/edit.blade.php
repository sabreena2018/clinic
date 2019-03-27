@extends('backend.layouts.app')


@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ $reservation->type }} Reservation
                        <br>
                        <small class="text-muted">Insert available time</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->
            <!--row-->

            <hr/>

            {{ html()->form('POST', route('admin.reservation.storeItems'))->class('form-horizontal')->open() }}
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-5">
                            <h4 class="card-title mb-0">
                                Clinic Management
                                <small class="text-muted">Create Clinic</small>
                            </h4>
                        </div><!--col-->
                    </div><!--row-->

                    <hr>

                    <div class="row mt-4">
                        <div class="col">
                            {{--<div class="form-group row">--}}
                                {{--{{ html()->label(__('validation.attributes.backend.access.roles.name'))--}}
                                    {{--->class('col-md-2 form-control-label')--}}
                                    {{--->for('name') }}--}}

                                {{--<div class="col-md-4">--}}
                                    {{--{{ html()->text('name')--}}
                                        {{--->class('form-control')--}}
                                        {{--->placeholder(__('validation.attributes.backend.access.roles.name'))--}}
                                        {{--->attribute('maxlength', 191)--}}
                                        {{--->required()--}}
                                        {{--->autofocus() }}--}}
                                {{--</div><!--col-->--}}
                            {{--</div><!--form-group-->--}}

                            <input type="hidden" name="aaaaa" id="wmewwwthod" value="wwwwww">

                            <div class="row">

                                <div class="col-md-4">
                                    {{ html()->text('Times')
                                        ->class('form-control')
                                        ->id('time')
                                        ->autofocus() }}
                                </div><!--col-->





                                <div class="col-md-3">
                                    <button type="button" id="button_insert" class="btn btn-warning">Insert</button>
                                </div>


                                <div class="col-md-3">
                                    <ul class="list-group" id="mylist">

                                    </ul>
                                </div><!--col-->
                            </div><!--row-->

                            <br><br><br>


                        </div><!--card-body-->


                        </div><!--col-->
                    </div><!--row-->
                </div><!--card-body-->

                <div class="card-footer">
                    <div class="row">
                        <div class="col">
                            {{ form_cancel(route('admin.reservation.index'), __('buttons.general.cancel')) }}
                        </div><!--col-->

                        <div class="col text-right">
                            {{ form_submit(__('buttons.general.crud.create')) }}
                        </div><!--col-->
                    </div><!--row-->
                </div><!--card-footer-->
            </div><!--card-->
            {{ html()->form()->close() }}


        <script type="application/javascript">
            var listitems = [];

            $(document).ready(function () {
                $('.select2_class_specialties').select2({
                    placeholder: "Select Specialties",
                    tags: true
                });

                let body = $('body');

                body.on('click', '#button_insert', function (e) {
                    var node = document.createElement("LI");
                    var time = document.getElementById('time');
                    var textnode = document.createTextNode(time.value);
                    node.setAttribute('class','list-group-item active');
                    node.appendChild(textnode);
                    document.getElementById("mylist").appendChild(node);
                    listitems.push(time.value);
                    time.value = "";

                    var text = '<input type="hidden" name="list" value='+ time.value() +'fieldvalue" />';
                    $('#form').append(text);

//                     var input = document.createElement("input");
//                     input.setAttribute("type", "hidden");
//                     input.setAttribute("name", "list");
//                     input.setAttribute("value", time.value());
//
// //append to form element that you want .
//                     document.getElementById("chells").appendChild(input);

                });

                body.on('click', '#button_done', function (e) {
                    if( $('#mylist li').length >= 1 ){


{{--                        {{ action('Auth\Role\ReservationController@index') }}--}}


                        {{--$.ajax({--}}
                            {{--url:'{{route('admin.reservation.index')}}?view=true',--}}
                            {{--type: "get",--}}

                        {{--});--}}
                    }
                });




            });
        </script>




        @endsection


        @push('after-styles')



            <style>
                .select2-container {
                    width: 100% !important;
                }
            </style>
    @endpush