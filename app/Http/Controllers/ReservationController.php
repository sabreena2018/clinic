<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use App\ClinicNurse;
use App\LabRegistration;
use App\Models\Auth\Clinic;
use App\Models\Auth\ClinicUser;
use App\Models\Auth\Lab;
use App\Models\Auth\PrivateDoctor;
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
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Nullable;

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
        $labsOwnerIds = null;

        switch ($type) {
            case "admin":
                $type="";
                break;
            case "owner":
                $type = "owner";
                $resObj = Clinic::query()->where('owner_id',$user_id)->pluck('id')->toArray();
                $labsOwnerIds = Lab::query()->where('owner_id',$user_id)->pluck('id')->toArray();
                $private_doctorsIds = Clinic::query()
                    ->where('clinics.owner_id', $user_id)
                    ->join('clinic_specialties', 'clinics.id', '=', 'clinic_specialties.clinic_id')
                    ->join('user_clinic_specialties', 'clinic_specialties.id', '=', 'user_clinic_specialties.clinic_specialties_id')
                    ->pluck('user_clinic_specialties.user_id')
                    ->toArray();


                $nurseIds = ClinicNurse::query()
                    ->where('clinic_id',$user_id)
                    ->pluck('clinic_nurse.nurse_id')
                    ->toArray();

                break;
            case
                $type = "doctor";
                $resObj = $user_id;
        }

        $reservations = Reservations::query()
            ->when($type,function ($q) use ($type,$resObj,$labsOwnerIds,$private_doctorsIds,$nurseIds){
                if ($type = "owner"){
                    $res_id = ClinicUser::query()
                        ->whereIn('clinic_id',$resObj)
                        ->pluck('reservation_id')->toArray();

                    $labs = LabRegistration::query()
                        ->whereIn('lab_id',$labsOwnerIds)
                        ->pluck('reservation_id')->toArray();

                    $private_doctors = PrivateDoctorRegistration::query()
                        ->whereIn('doctor_id',$private_doctorsIds)
                        ->pluck('reservation_id')->toArray();


                    $nurses = NurseRegistration::query()
                        ->whereIn('nurse_id',$nurseIds)
                        ->pluck('reservation_id')->toArray();



                }
                if (isAdmin()){
                    return $q;
                }

                $res_id = array_merge($res_id,$labs,$private_doctors,$nurses);

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

        $private_doctorsIds = Clinic::query()
            ->where('clinics.owner_id', \Auth::user()->id)
            ->join('clinic_specialties', 'clinics.id', '=', 'clinic_specialties.clinic_id')
            ->join('user_clinic_specialties', 'clinic_specialties.id', '=', 'user_clinic_specialties.clinic_specialties_id')
            ->pluck('user_clinic_specialties.user_id')
            ->toArray();

        $nurseIds = ClinicNurse::query()
            ->where('clinic_id',\Auth::user()->id)
            ->pluck('clinic_nurse.nurse_id')
            ->toArray();


        $appointments = null;
        if ($reservation->type == 'clinic'){
            $appointments = ClinicUser::query()
                ->select('clinic_user.appointment','clinic_user.time')
                ->join('clinics','clinic_user.clinic_id','=','clinics.id')
                ->where('clinics.owner_id',\Auth::user()->id)
                ->join('reservations','clinic_user.reservation_id','=','reservations.id')
                ->where('reservations.status','confirm-treatment')
                ->orderBy('clinic_user.appointment', 'desc')
                ->get();

        }elseif ($reservation->type == 'lab'){
            $appointments = LabRegistration::query()
                ->select('lab_registrations.appointment','lab_registrations.time')
                ->join('labs','lab_registrations.lab_id','=','labs.id')
                ->where('labs.owner_id',\Auth::user()->id)
                ->join('reservations','lab_registrations.reservation_id','=','reservations.id')
                ->where('reservations.status','confirm-treatment')
                ->orderBy('lab_registrations.appointment', 'desc')
                ->get();
        }
        elseif ($reservation->type == 'private-doctor'){
            $appointments = PrivateDoctorRegistration::query()
                ->select('private_doctor_registrations.appointment','private_doctor_registrations.time')
                ->whereIn('doctor_id',$private_doctorsIds)
                ->join('reservations','private_doctor_registrations.reservation_id','=','reservations.id')
                ->where('reservations.status','confirm-treatment')
                ->orderBy('private_doctor_registrations.appointment', 'desc')
                ->get();
        }
        elseif ($reservation->type == 'nurse'){
            $appointments = NurseRegistration::query()
                ->select('nurse_registrations.appointmentFrom','nurse_registrations.appointmentTo')
                ->whereIn('nurse_id',$nurseIds)
                ->join('reservations','nurse_registrations.reservation_id','=','reservations.id')
                ->where('reservations.status','confirm-treatment')
                ->orderBy('nurse_registrations.appointmentFrom', 'desc')
                ->get();
        }


        return view('reservation.edit', compact('reservation','appointments'));
    }


    public function storeItems(Request $request)
    {

        $res = Reservations::find($request->reservation_id);
        $res->status = "waiting-choose";
        $res->save();


        foreach ($request->listitem as $item){
            TimesResgistration::create([
                'reservation_id' => $request->reservation_id,
                'time' => Carbon::parse($item),
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

    public function removeRequestsIndex(Request $request)
    {

        $reservations =  Reservations::query()
            ->where('status','remove-rejected')
            ->orderBy('status', 'asc')
            ->paginate(25);


        if ($request->get('view', false)) {
            return view('reservation.partial.removeRequestsIndexTable',compact('reservations'));
        }

        return view('reservation.removeRequestsIndex');




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
        $reservation = Reservations::find($request->reservation_id);
        $type = $reservation->type;

        if ($request->removeStatus == 'remove-rejected'){
            $reservation->status = 'remove-rejected';
            $reservation->save();
        }

        else{
            if ($reservation->status == 'request-remove'){
                $reservation->delete();

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

            }
                $reservation->status = 'request-remove';
                $reservation->save();
            }

    }





}
