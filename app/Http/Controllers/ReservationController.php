<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use App\LabRegistration;
use App\Models\Auth\Clinic;
use App\Models\Auth\ClinicUser;
use App\Models\Auth\Lab;
use App\Models\Auth\Role;
use App\Http\Controllers\Controller;
use App\Events\Backend\Auth\Role\RoleDeleted;
use App\NurseRegistration;
use App\PrivateDoctorRegistration;
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
        $user_id = \Auth::user()->id;
        $type = \Auth::user()->type;
        $resObj = null;


        switch ($type) {
            case "admin":
                $type="";
                break;
            case "owner":
                $type = "clinic";
                $resObj = Clinic::query()->where('owner_id',$user_id)->pluck('id')->toArray();
                break;
            case
                $type = "doctor";
                $resObj = user_id;
        }

        $reservations = Reservations::query()
            ->when($type,function ($q) use ($type){
                return $q->where('type',$type);
            })
            ->when($type,function ($q) use ($type,$resObj){
                if ($type = "clinic"){
                    $res_id = ClinicUser::query()
                        ->whereIn('clinic_id',$resObj)
                        ->pluck('reservation_id')->toArray();
                }
                if (isAdmin()){
                    return $q;
                }

                return $q->whereIn('id',$res_id);
            })
            ->orderBy('status', 'asc')
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

        $res = Reservations::find($request->reservation_id);
        $res->status = "waiting-choose";
        $res->save();


        foreach ($request->listitem as $item){
            TimesResgistration::create([
                'reservation_id' => $request->reservation_id,
                'time' => $item,
            ]);
        }

    }


    public function confirmReservation(Request $request)
    {
        $res = Reservations::find($request->reservation_id);
        $res->status = "require-confirm-owner";
        $res->save();
    }

    public function confirmPaymentOwner(Request $request)
    {
        $res = Reservations::find($request->reservation_id);
        $res->status = "confirm-treatment";
        $res->save();
        return view('reservation.index');
    }


    public function storeTimeUserIndex(Request $request)
    {
        $times = TimesResgistration::query()
        ->where('reservation_id',$request->id)->get();


        return view('reservation.chooseTimeUser',compact('times'));

    }

    public function chooseTimeUser(Request $request)
    {

        if ($request->type == 'lab'){
            LabRegistration::query()->where('reservation_id',$request->reservation_id)
                ->update(['time'=>$request->time]);
        }
        elseif ($request->type == 'clinic'){
            ClinicUser::query()->where('reservation_id',$request->reservation_id)
            ->update(['time'=>$request->time]);
        }
        elseif ($request->type == 'private-doctor'){
            PrivateDoctorRegistration::query()->where('reservation_id',$request->reservation_id)
                ->update(['time'=>$request->time]);
        }
        elseif ($request->type == 'nurse'){
            NurseRegistration::query()->where('reservation_id',$request->reservation_id)
                ->update(['time'=>$request->time]);
        }

        $res = Reservations::find($request->reservation_id);
        $res->status = 'require-confirm';
        $res->save();

    }

    public function destroy(Request $request)
    {
        $type = Reservations::find($request->reservation_id)->type;

        if ($type == 'lab'){
            LabRegistration::query()->where('reservation_id',$request->reservation_id)
                ->delete();
        }
        elseif ($type == 'clinic'){
            ClinicUser::query()->where('reservation_id',$request->reservation_id)
                ->delete();
        }
        elseif ($type == 'private-doctor'){
            PrivateDoctorRegistration::query()->where('reservation_id',$request->reservation_id)
                ->delete();
        }
        elseif ($type == 'nurse'){
            NurseRegistration::query()->where('reservation_id',$request->reservation_id)
                ->delete();
        }

        $res = Reservations::find($request->reservation_id);
        $res->delete();
    }





}
