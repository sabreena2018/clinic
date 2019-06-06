<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use App\Http\Requests\Backend\Auth\Role\ClinicRequest;
use App\Http\Requests\Backend\Auth\Role\PrivateDoctorRequest;
use App\Models\Auth\Clinic;
use App\Models\Auth\PrivateDoctor;
use App\Models\Auth\Role;
use App\Http\Controllers\Controller;
use App\Events\Backend\Auth\Role\RoleDeleted;
use App\Models\Auth\User;
use App\PrivateDoctorRegistration;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Http\Requests\Backend\Auth\Role\StoreRoleRequest;
use App\Http\Requests\Backend\Auth\Role\ManageRoleRequest;
use App\Http\Requests\Backend\Auth\Role\UpdateRoleRequest;
use App\Reservations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class PrivateDoctorController.
 */
class PrivateDoctorController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */


    public function indexRegistration(Request $request)
    {

        $specialtiesF = $request->get('specialtiesF');
        $doctorF = $request->get('doctorF');
        $dateF = $request->get('dateF');
        $TperiodF = $request->get('TperiodF');
        $cityF = $request->get('cityF');
        $service_locationF = $request->get('service_locationF');


        $privateDoctors= PrivateDoctorRegistration::query()
            ->where('user_id',\Auth::user()->id)
            ->when($doctorF,function ($q) use ($doctorF){
                return $q->where('doctor_id',$doctorF);
            })
            ->when($dateF,function ($q) use ($dateF){
                return $q->where('appointment',$dateF);
            })
            ->when($TperiodF,function ($q) use ($TperiodF){
                return $q->where('Tperiod',$TperiodF);
            })
            ->when($cityF,function ($q) use ($cityF){
                return $q->where('city',$cityF);
            })
            ->when($service_locationF,function ($q) use ($service_locationF){
                return $q->where('serviceL',$service_locationF);
            })

            ->orderBy('id', 'asc')
            ->paginate(25);


        if ($request->get('view', false)) {
            return view('private-doctor.partial.table-registration', compact('privateDoctors'));
        }
        return view('private-doctor.index', compact('privateDoctors'));

    }


    public function index(ClinicRequest $request)
    {

        $doctorsFilter = $request->get('doctors', []);
        $specialtiesFilter = $request->get('specialties', []);
        $countriesFilter = $request->get('countries', []);
        $cityFilter = $request->get('city', []);


        $user = \Auth::user();
        $privateDoctors = PrivateDoctor::query()
            ->when($doctorsFilter, function ($q) use ($doctorsFilter) {
                return $q->whereHas('specialties', function ($query) use ($doctorsFilter) {
                    $query = $query
                        ->whereIn('user_specialties.user_id', $doctorsFilter);
                    return $query;
                });
            })
            ->when($specialtiesFilter, function ($q) use ($specialtiesFilter) {
                return $q->whereHas('specialties', function ($query) use ($specialtiesFilter) {
                    return $query->whereIn('specialties.id', $specialtiesFilter);
                });
            })
            ->when($countriesFilter, function ($q) use ($countriesFilter) {
                return $q->whereIn('users.country_id', $countriesFilter);
            })
            ->when($cityFilter, function ($q) use ($cityFilter) {
                return $q->where('users.city', 'LIKE', "%$cityFilter%");
            })
            ->when($user->type == 'patient', function ($q) use ($user) {
                return $q->where('users.approved', 1);
            })
            ->when($user->type == 'private-doctor', function ($q) use ($user) {
                return $q->where('users.id', $user->id);
            })
            ->orderBy('id', 'asc')
            ->paginate(25);


        if ($request->get('view', false)) {
            return view('private-doctor.partial.table', compact('privateDoctors'));
        }
        return view('private-doctor.index', compact('privateDoctors'));

    }

    public function create(Request $request, $privateDoctor)
    {
        $user = PrivateDoctor::find($privateDoctor);
        return view('private-doctor.create', compact('user'));
    }


    public function show(ClinicRequest $request, Clinic $privateDoctor)
    {
        return view('private-doctor.show', compact('private-doctor'));
    }


    public function getDoctorsDependOnSpecialties(Request $request)
    {
        $db = DB::select("select id,first_name,last_name from users where id in (select user_id from user_specialties where specialties_id= {$request->specialties_id})");
        return $db;

    }

    public function storePrivateDoctorReg(PrivateDoctorRequest $request)
    {


        $res = Reservations::create([
            'type' => 'private-doctor',
            'status' => 'require-time',
            'user_id' => \Auth::user()->id,
            'preferred_time' => Carbon::parse($request->get('preferred-time')),
            'appointment' => $request->get('date'),
        ]);

        PrivateDoctorRegistration::create([
            'reservation_id' => $res->id,
            'doctor_id' => $request->doctor,
            'Tperiod' => $request->Tperiod,
            'user_id' => \Auth::user()->id,
            'appointment' => $request->get('date'),
            'city' => $request->city,
            'serviceL' => $request->service_location,
            'time' => '',
        ]);

        return redirect()->route('admin.registration.show', ['type' => 'private-doctor'])->withFlashSuccess('The lab was successfully saved.');


    }


    public function store(PrivateDoctorRequest $request, $privateDoctor)
    {

        $privateDoctor = PrivateDoctor::find($privateDoctor);

        $specialties = $request->get('specialties', []);
        $specialtiesIds = array_filter($specialties, 'is_numeric');
        $specialtiesNames = [];


        foreach ($specialties as $specialty) {
            if (!is_numeric($specialty)) {
                $specialtiesNames[] = $specialty;
            }
        }


        foreach ($specialtiesNames as $name) {
            $object = \App\Models\Auth\Specialties::query()->create([
                'name' => $name,
            ]);

            $specialtiesIds[] = $object->id;
        }

        $privateDoctor
            ->update([
                'country_id' => $request->get('country_id'),
                'city' => $request->get('city', null),
                'description' => $request->get('description', null),
                'info_filled' => true,
            ]);


        $privateDoctor->specialties()->sync($specialtiesIds);


        return redirect()->route('admin.private-doctor.index')->withFlashSuccess('The private-doctor was successfully saved.');
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role $role
     *
     * @return mixed
     */
    public function edit(ClinicRequest $request, Clinic $privateDoctor)
    {

        return view('private-doctor.edit', compact('private-doctor'));
    }


    public function update(ClinicRequest $request, PrivateDoctor $privateDoctor)
    {

        $privateDoctor
            ->update(
                [
                    'name' => $request->get('name'),
                    'country_id' => $request->get('country_id'),
                    'city' => $request->get('city', null),
                    'description' => $request->get('description', null),
                ]
            );


        $specialties = $request->get('specialties', []);
        $specialtiesIds = array_filter($specialties, 'is_numeric');
        $specialtiesNames = [];


        foreach ($specialties as $specialty) {
            if (!is_numeric($specialty)) {
                $specialtiesNames[] = $specialty;
            }
        }

        $privateDoctor->specialties()->sync($specialtiesIds);

        $ids = [];
        foreach ($specialtiesNames as $name) {
            $object = \App\Models\Auth\Specialties::query()->create([
                'name' => $name,
            ]);

            $ids[] = $object->id;
        }


        $privateDoctor->specialties()->syncWithoutDetaching($ids);


        return redirect()->route('admin.private-doctor.index')->withFlashSuccess('The private-doctor was successfully updated.');
    }


    public function destroy(ClinicRequest $request, Clinic $privateDoctor)
    {

        $privateDoctor->delete();

        return redirect()->route('admin.private-doctor.index')->withFlashSuccess('The private-doctor was successfully deleted.');
    }


    public function approve($privateDoctor)
    {
        $privateDoctor = PrivateDoctor::find($privateDoctor);
        if (!$privateDoctor->approved) {
            $privateDoctor->update(['approved' => true]);
        }

        return response()->json(['message' => 'The private-doctor was successfully approved.'], 200);
    }


    public function reject($privateDoctor)
    {
        $privateDoctor = PrivateDoctor::find($privateDoctor);

        if ($privateDoctor->approved) {
            $privateDoctor->update(['approved' => false]);
        }

        return response()->json(['message' => 'The private-doctor was successfully rejected.'], 200);
    }




}
