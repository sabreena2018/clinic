@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.access.roles.management'))

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        Reservations
                    </h4>
                </div>
            </div>
            <br>


            <div class="load-table">

            </div>





        </div>
    </div>


    <script>
        $(document).ready(function () {


            let body = $('body');

            $('.load-table').load('{{route('admin.reservation.index')}}?view=true', function () {
                intDeleteButton();
            });

           });


    </script>


@endsection


