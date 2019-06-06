<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use App\Http\Requests\Backend\Auth\Role\LabRequest;
use App\LabRegistration;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

/**
 * Class LabController.
 */
class LabController extends Controller
{
    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function index(ClinicRequest $request)
    {

        $labFilter = $request->get('lab');
        $dateFilter = $request->get('date');
        $TperiodFilter = $request->get('Tperiod');


        $labs = LabRegistration::query()
            ->where('user_id',\Auth::user()->id)
            ->when($labFilter,function ($q) use ($labFilter){
                return $q->whereIn('lab_id',$labFilter);
            })

            ->when($dateFilter,function ($q) use ($dateFilter){
                return $q->where('appointment',$dateFilter);
            })


            ->when($TperiodFilter,function ($q) use ($TperiodFilter){
                return $q->where('Tperiod',$TperiodFilter);
            })


            ->orderBy('id', 'asc')
            ->paginate(25);

        if ($request->get('view', false)) {
            return view('lab.partial.table', compact('labs'));
        }

        return view('lab.index', compact('labs'));

    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function create(ClinicRequest $request)
    {
        return view('lab.create');
    }


    public function show(ClinicRequest $request, Lab $lab)
    {
        return view('lab.show', compact('lab'));
    }

    public function storeLabReg(LabRequest $request){


        $res = Reservations::create([
            'type' => 'lab',
            'status' => 'require-time',
            'user_id' => \Auth::user()->id,
            'preferred_time' => Carbon::parse($request->get('preferred-time')),
            'appointment' => $request->get('date'),
        ]);

        LabRegistration::create([

            'reservation_id' => $res->id,
            'lab_id' => $request->get('labs'),
            'user_id' => \Auth::user()->id,
            'appointment' => $request->get('date'),
            'Tperiod' => $request->get('Tperiod'),

        ]);

        return redirect()->route('admin.registration.show', ['type' => 'lab'])->withFlashSuccess('The lab was successfully saved.');

    }


    public function store(ClinicRequest $request)
    {

        $lab = Lab::query()
            ->create([
                'name' => $request->get('name'),
                'owner_id' => currentUser()->id,
                'country_id' => $request->get('country_id'),
                'city' => $request->get('city', null),
                'description' => $request->get('description', null)
            ]);

        $specialties = $request->get('specialties', []);
        $specialtiesIds = array_filter($specialties, 'is_numeric');
        $specialtiesNames = [];


        foreach ($specialties as $specialty) {
            if (!is_numeric($specialty)) {
                $specialtiesNames[] = $specialty;
            }
        }

        $lab->specialties()->sync($specialtiesIds);

        $ids = [];
        foreach ($specialtiesNames as $name) {
            $object = \App\Models\Auth\Specialties::query()->create([
                'name' => $name,
            ]);

            $ids[] = $object->id;
        }


        $lab->specialties()->syncWithoutDetaching($ids);


        return redirect()->route('admin.lab.index')
            ->withFlashSuccess('The lab was successfully saved.');
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role $role
     *
     * @return mixed
     */
    public function edit(ClinicRequest $request, Lab $lab)
    {
        return view('lab.edit', compact('lab'));
    }

    public function update(ClinicRequest $request, Lab $lab)
    {
        $lab->update([
            'name' => $request->get('name'),
            'country_id' => $request->get('country_id'),
            'city' => $request->get('city', null),
            'description' => $request->get('description', null),
        ]);


        $specialties = $request->get('specialties', []);
        $specialtiesIds = array_filter($specialties, 'is_numeric');
        $specialtiesNames = [];


        foreach ($specialties as $specialty) {
            if (!is_numeric($specialty)) {
                $specialtiesNames[] = $specialty;
            }
        }

        $lab->specialties()->sync($specialtiesIds);

        $ids = [];
        foreach ($specialtiesNames as $name) {
            $object = \App\Models\Auth\Specialties::query()->create([
                'name' => $name,
            ]);

            $ids[] = $object->id;
        }


        $lab->specialties()
            ->syncWithoutDetaching($ids);


        return redirect()->route('admin.lab.index')
            ->withFlashSuccess('The lab was successfully updated.');
    }


    public function destroy(ClinicRequest $request, Lab $lab)
    {
        $lab->delete();

        return redirect()->route('admin.lab.index')
            ->withFlashSuccess('The lab was successfully deleted.');
    }


    public function approve(Lab $lab)
    {
        if (!$lab->approved) {
            $lab->approved = true;
            $lab->save();
        }

        return response()
            ->json(['message' => 'The lab was successfully approved.'], 200);
    }


    public function reject(Lab $lab)
    {
        if ($lab->approved) {
            $lab->approved = false;
            $lab->save();
        }

        return response()
            ->json(['message' => 'The lab was successfully rejected.'], 200);
    }
}
