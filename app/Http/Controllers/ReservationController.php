<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use App\Models\Auth\Lab;
use App\Models\Auth\Role;
use App\Http\Controllers\Controller;
use App\Events\Backend\Auth\Role\RoleDeleted;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Http\Requests\Backend\Auth\Role\ClinicRequest;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Http\Requests\Backend\Auth\Role\StoreRoleRequest;
use App\Http\Requests\Backend\Auth\Role\ManageRoleRequest;
use App\Http\Requests\Backend\Auth\Role\UpdateRoleRequest;
use App\Reservations;
use App\TimesResgistration;
use Illuminate\Http\Request;

/**
 * Class RegistrationController.
 */
class ReservationController extends Controller
{
    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $reservations = Reservations::orderBy('id', 'asc')
            ->paginate(25);


        if ($request->get('view', false)) {
            return view('reservation.partial.table', compact('reservations'));
        }
        return view('reservation.index', compact('reservations'));
    }


    public function show(Request $request, $type)
    {
        return view('reservation.show.' . $type);
    }

    public function edit(Request $request, Reservations $reservation)
    {
        return view('reservation.edit', compact('reservation'));
    }


    public function storeItems(Request $request)
    {

        dd($request);
//        $res = Reservations::find($reservation->id);
//        $res->status = "waiting-choose";
//        $res->save();


//        TimesResgistration::create([
//
//            'reservation_id' => $res->id,
//            'time' => '',
//
//        ]);

        return view('reservation.index');
    }



}
