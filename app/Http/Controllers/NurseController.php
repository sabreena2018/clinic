<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use App\Helpers\Auth\Auth;
use App\Http\Requests\Backend\Auth\Role\ClinicRequest;
use App\Http\Requests\Backend\Auth\Role\NurseRegRequest;
use App\Http\Requests\Backend\Auth\Role\NurseRequest;
use App\Models\Auth\Clinic;
use App\Models\Auth\Nurse;
use App\Models\Auth\Role;
use App\Http\Controllers\Controller;
use App\Events\Backend\Auth\Role\RoleDeleted;
use App\Models\Auth\User;
use App\NurseRegistration;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Http\Requests\Backend\Auth\Role\StoreRoleRequest;
use App\Http\Requests\Backend\Auth\Role\ManageRoleRequest;
use App\Http\Requests\Backend\Auth\Role\UpdateRoleRequest;
use App\Reservations;
use Illuminate\Http\Request;

/**
 * Class NurseController.
 */
class NurseController extends Controller
{
    public function __construct()
    {
    }


    public function nurseIndexRegistration(Request $request)
    {
        $nurseF = $request->get('nurseF');
        $dateFromF = $request->get('dateFromF');
        $dateToF = $request->get('dateToF');
        $cityF = $request->get('cityF');


        $nurseRegs = NurseRegistration::query()
            ->where('user_id',\Auth::user()->id)
            ->when($nurseF,function ($q) use ($nurseF){
                    return $q->where('nurse_id',$nurseF);
            })
            ->when($dateFromF,function ($q) use ($dateFromF){
                return $q->where('appointmentFrom',$dateFromF);
            })
            ->when($dateToF,function ($q) use ($dateToF){
                return $q->where('appointmentTo',$dateToF);
            })
            ->when($cityF,function ($q) use ($cityF){
                return $q->where('city',$cityF);
            })
            ->orderBy('id', 'asc')
            ->paginate(25);


        if ($request->get('view', false)) {
            return view('nurse.partial.tableReg', compact('nurseRegs'));
        }
        return view('nurse.index', compact('nurseReg'));


    }




    public function index(ClinicRequest $request)
    {

        $doctorsFilter = $request->get('doctors', []);
        $specialtiesFilter = $request->get('specialties', []);
        $countriesFilter = $request->get('countries', []);
        $cityFilter = $request->get('city', []);


        $user = \Auth::user();
        $nurses = Nurse::query()
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
            ->when($user->type == 'nurse', function ($q) use ($user) {
                return $q->where('users.id', $user->id);
            })
            ->orderBy('id', 'asc')
            ->paginate(25);


        if ($request->get('view', false)) {
            return view('nurse.partial.table', compact('nurses'));
        }
        return view('nurse.nurseIndex', compact('nurses'));

    }

    public function create(Request $request, $nurse)
    {
        $user = Nurse::find($nurse);
        return view('nurse.create', compact('user'));
    }


    public function show(ClinicRequest $request, Clinic $nurse)
    {
        return view('nurse.show', compact('nurse'));
    }


    public function nurseStoreReg(NurseRegRequest $request)
    {
        $res = Reservations::create([
            'type' => 'nurse',
            'status' => 'require-confirm-owner',
            'user_id' => \Auth::user()->id,
            'appointment' => $request->get('dateFrom'),
        ]);

        NurseRegistration::create([

            'reservation_id' => $res->id,
            'nurse_id' => $request->get('nurse'),
            'user_id' => \Auth::user()->id,
            'appointmentFrom' => $request->get('dateFrom'),
            'appointmentTo' => $request->get('dateTo'),
            'city' => $request->get('city'),

        ]);

        return redirect()->route('admin.registration.show', ['type' => 'nurse'])->withFlashSuccess('The lab was successfully saved.');

    }


    public function store(NurseRequest $request, $nurse)
    {

        $nurse = Nurse::find($nurse);

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

        $nurse
            ->update([
                'country_id' => $request->get('country_id'),
                'city' => $request->get('city', null),
                'description' => $request->get('description', null),
                'info_filled' => true,
            ]);


        $nurse->specialties()->sync($specialtiesIds);


        return redirect()->route('admin.nurse.index')->withFlashSuccess('The nurse was successfully saved.');
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role $role
     *
     * @return mixed
     */
    public function edit(ClinicRequest $request, Clinic $nurse)
    {

        return view('nurse.edit', compact('nurse'));
    }


    public function update(ClinicRequest $request, Nurse $nurse)
    {

        $nurse
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

        $nurse->specialties()->sync($specialtiesIds);

        $ids = [];
        foreach ($specialtiesNames as $name) {
            $object = \App\Models\Auth\Specialties::query()->create([
                'name' => $name,
            ]);

            $ids[] = $object->id;
        }


        $nurse->specialties()->syncWithoutDetaching($ids);


        return redirect()->route('admin.nurse.index')->withFlashSuccess('The nurse was successfully updated.');
    }


    public function destroy(ClinicRequest $request, Clinic $nurse)
    {

        $nurse->delete();

        return redirect()->route('admin.nurse.index')->withFlashSuccess('The nurse was successfully deleted.');
    }


    public function approve($nurse)
    {
        $nurse = Nurse::find($nurse);
        if (!$nurse->approved) {
            $nurse->update(['approved' => true]);
        }

        return response()->json(['message' => 'The nurse was successfully approved.'], 200);
    }


    public function reject($nurse)
    {
        $nurse = Nurse::find($nurse);

        if ($nurse->approved) {
            $nurse->update(['approved' => false]);
        }

        return response()->json(['message' => 'The nurse was successfully rejected.'], 200);
    }


}
