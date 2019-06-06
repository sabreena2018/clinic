<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use App\Http\Requests\Backend\Auth\Role\ClinicUserRequest;
use App\Methods\ClinicMethods;
use App\Methods\DoctorMethods;
use App\Models\Auth\Clinic;
use App\Models\Auth\ClinicUser;
use App\Models\Auth\Lab;
use App\Models\Auth\Patient;
use App\Models\Auth\Role;
use App\Models\Auth\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\AppointmentsResource;
use App\Events\Backend\Auth\Role\RoleDeleted;
use App\Models\Auth\Specialties;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Http\Requests\Backend\Auth\Role\ClinicRequest;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Http\Requests\Backend\Auth\Role\StoreRoleRequest;
use App\Http\Requests\Backend\Auth\Role\ManageRoleRequest;
use App\Http\Requests\Backend\Auth\Role\UpdateRoleRequest;
use App\Reservations;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Logger;

/**
 * Class ClinicController.
 */
class ClinicController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function index(ClinicRequest $request)
    {
        $doctorsFilter = $request->get('doctors', []);
        $clinicsFilter = $request->get('clinics', []);
        $specialtiesFilter = $request->get('specialties', []);
        $countriesFilter = $request->get('countries', []);
        $cityFilter = $request->get('city', []);
        $appointment = $request->get('appointment', []);
        $serviceLocation = $request->get('service_location', []);

        $from = $to = null;

        if ($appointment) {
            list($from, $to) = explode('-', $appointment);
            $from = Carbon::parse($from);
            $to = Carbon::parse($from);
        }


        $user = \Auth::user();
        $clinics = Clinic::query()
            ->when($clinicsFilter, function ($q) use ($clinicsFilter) {
                return $q->whereIn('clinics.id', $clinicsFilter);
            })
            ->when($doctorsFilter, function ($q) use ($doctorsFilter) {
                return $q->whereHas('specialties', function ($query) use ($doctorsFilter) {
                    $query = $query->join('user_clinic_specialties', 'clinic_specialties.id', '=', 'user_clinic_specialties.clinic_specialties_id')
                        ->whereIn('user_clinic_specialties.user_id', $doctorsFilter);
                    return $query;
                });
            })
            ->when($specialtiesFilter, function ($q) use ($specialtiesFilter) {
                return $q->whereHas('specialties', function ($query) use ($specialtiesFilter) {
                    return $query->whereIn('specialties.id', $specialtiesFilter);
                });
            })
            ->when($countriesFilter, function ($q) use ($countriesFilter) {
                return $q->whereIn('clinics.country_id', $countriesFilter);
            })
            ->when($cityFilter, function ($q) use ($cityFilter) {
                return $q->where('clinics.city', 'LIKE', "%$cityFilter%");
            })
            ->when($user->type == 'owner', function ($q) use ($user) {
                return $q->where('owner_id', $user->id);
            })
            ->when($user->type == 'patient', function ($q) use ($user) {
                return $q->where('approved', 1);
            })
            ->when($user->type == 'doctor', function ($q) use ($user) {
                return $q->whereHas('specialties', function ($query) {
                    $query = $query->join('user_clinic_specialties', 'clinic_specialties.id', '=', 'user_clinic_specialties.clinic_specialties_id')
                        ->whereIn('user_clinic_specialties.user_id', [currentUser()->id]);
                    return $query;
                });
            })
            ->when($serviceLocation, function ($q) use ($serviceLocation) {
                return $q->where('service_location', $serviceLocation);
            })
            ->orderBy('id', 'asc')
            ->paginate(25);

        if ($request->get('view', false)) {
            return view('clinic.partial.tableIndex', compact('clinics'));
        }
        return view('clinic.index', compact('clinics'));
    }


    public function indexUserClinic(Request $request)
    {

        $doctorsFilter = $request->get('doctors');
        $clinicsFilter = $request->get('clinics');
        $specialtiesFilter = $request->get('specialties');
        $countriesFilter = $request->get('countries');
        $cityFilter = $request->get('city');
        $date = $request->get('date');
        $Home_clinic = $request->get('Home_clinic');
        $Tperiod = $request->get('Tperiod');


        $userID = \Auth::user()->id;
        $clinics = ClinicUser::query()->where('user_id','=',$userID)
        ->when($doctorsFilter, function ($q) use ($doctorsFilter) {
            return $q->whereIn('doctor_id', $doctorsFilter);
        })
        ->when($clinicsFilter, function ($q) use ($clinicsFilter) {
            return $q->whereIn('clinic_id', $clinicsFilter);
        })
        ->when($specialtiesFilter, function ($q) use ($specialtiesFilter) {
            return $q->whereIn('specialties_id', $specialtiesFilter);
        })
        ->when($countriesFilter, function ($q) use ($countriesFilter) {
            return $q->where('country_id', $countriesFilter);
        })
        ->when($cityFilter, function ($q) use ($cityFilter) {
            return $q->where('city', $cityFilter);
        })
        ->when($date, function ($q) use ($date) {
            return $q->where('appointment', $date);
        })
        ->when($Tperiod, function ($q) use ($Tperiod) {
            return $q->where('Tperiod', $Tperiod);
        })
        ->when($Home_clinic, function ($q) use ($Home_clinic) {
            return $q->where('serviceL', $Home_clinic);
        })
        ->orderBy('id', 'asc')
        ->paginate(25);


        if ($request->get('view', false)) {
            return view('clinic.partial.table', compact('clinics'));
        }
        return view('clinic.index', compact('clinics'));
    }

    /**
     * @param ManageRoleRequest $request
     *
     * @return mixed
     */
    public function create(ClinicRequest $request)
    {
        return view('clinic.create');
    }


    public function show(ClinicRequest $request, Clinic $clinic)
    {
        return view('clinic.show', compact('clinic'));
    }


    public function patientIndex()
    {

        if (\Auth::user()->type == 'owner'){

            $clinicsIds = Clinic::query()
                ->select('id')
                ->where('owner_id',\Auth::user()->id)
                ->get();

            $labsIds = Lab::query()
                ->select('id')
                ->where('owner_id',\Auth::user()->id)
                ->get();

        }


        $patientsIds = Reservations::query()
            ->select('reservations.user_id')
            ->join('clinic_user','reservations.id','=','clinic_user.reservation_id')
            ->whereIn('clinic_user.clinic_id',$clinicsIds)
            ->distinct()
            ->get()->toArray();


        $patientsIdsLab = Reservations::query()
            ->select('reservations.user_id')
            ->join('lab_registrations','reservations.id','=','lab_registrations.reservation_id')
            ->whereIn('lab_registrations.lab_id',$labsIds)
            ->distinct()
            ->get()->toArray();


        foreach ($patientsIdsLab as $patientsIdsL){
            if (!in_array($patientsIdsL,$patientsIds))
                array_push($patientsIds,$patientsIdsL);
        }


        $patients = Patient::findMany($patientsIds);

        return view('patient.patientIndex', compact('patients'));
    }


    public function storeClinicUser(ClinicUserRequest $request)
    {

        logger($request);

        $res = Reservations::create([
            'type' => 'clinic',
            'status' => 'require-time',
            'user_id' => \Auth::user()->id,
            'preferred_time' => Carbon::parse($request->get('preferred-time')),
            'appointment' => $request->get('date'),
        ]);


        ClinicUser::create([
            'reservation_id' => $res->id,
            'clinic_id' => $request->get('clinics'),
            'user_id' => \Auth::user()->id,
            'doctor_id'=> $request->get('doctors'),
            'specialties_id' => $request->get('specialties'),
            'country' => $request->get('countries'),
            'city' => $request->get('city'),
            'appointment' => $request->get('date'),
            'Tperiod' => $request->get('Tperiod'),
            'serviceL' => $request->get('service_location'),
        ]);


        return redirect()->route('admin.registration.show', ['type' => 'clinic'])->withFlashSuccess('The clinic was successfully saved.');
    }



    public function store(ClinicRequest $request)
    {

        $clinic = Clinic::query()
            ->create([
                'name' => $request->get('name'),
                'owner_id' => currentUser()->id,
                'country_id' => $request->get('country_id'),
                'city' => $request->get('city', null),
                'description' => $request->get('description', null),
                'service_location' => $request->get('service_location', null)
            ]);

        $specialties = $request->get('specialties', []);
        $specialtiesIds = array_filter($specialties, 'is_numeric');
        $specialtiesNames = [];


        foreach ($specialties as $specialty) {
            if (!is_numeric($specialty)) {
                $specialtiesNames[] = $specialty;
            }
        }

        $clinic->specialties()->sync($specialtiesIds);

        $ids = [];
        foreach ($specialtiesNames as $name) {
            $object = \App\Models\Auth\Specialties::query()->create([
                'name' => $name,
            ]);

            $ids[] = $object->id;
        }


        $clinic->specialties()->syncWithoutDetaching($ids);


        return redirect()->route('admin.clinic.index')->withFlashSuccess('The clinic was successfully saved.');
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role $role
     *
     * @return mixed
     */
    public function edit(ClinicRequest $request, Clinic $clinic)
    {
        return view('clinic.edit', compact('clinic'));
    }


    public function update(ClinicRequest $request, Clinic $clinic)
    {
        $clinic
            ->update(
                [
                    'name' => $request->get('name'),
                    'country_id' => $request->get('country_id'),
                    'city' => $request->get('city', null),
                    'description' => $request->get('description', null),
                    'service_location' => $request->get('service_location', null),

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

        $clinic->specialties()->sync($specialtiesIds);

        $ids = [];
        foreach ($specialtiesNames as $name) {
            $object = \App\Models\Auth\Specialties::query()->create([
                'name' => $name,
            ]);

            $ids[] = $object->id;
        }


        $clinic->specialties()->syncWithoutDetaching($ids);


        return redirect()->route('admin.clinic.index')->withFlashSuccess('The clinic was successfully updated.');
    }


    public function destroy(ClinicRequest $request, Clinic $clinic)
    {
        $clinic->delete();

        return redirect()->route('admin.clinic.index')->withFlashSuccess('The clinic was successfully deleted.');
    }


    public function approve(Clinic $clinic)
    {
        if (!$clinic->approved) {
            $clinic->approved = true;
            $clinic->save();
        }

        return response()->json(['message' => 'The clinic was successfully approved.'], 200);
    }


    public function reject(Clinic $clinic)
    {
        if ($clinic->approved) {
            $clinic->approved = false;
            $clinic->save();
        }

        return response()->json(['message' => 'The clinic was successfully rejected.'], 200);
    }


    public function getAppointments(ClinicRequest $request, $clinicId)
    {
        $appointments = Clinic::find($clinicId)->appointments;

        return new AppointmentsResource($appointments);
    }

    public function confirmAppointment(ClinicRequest $request, $clinicId, $appointmentId)
    {
        $appointment = Appointment::find($appointmentId);

        $appointments = Appointment::where('group_code', $appointment->group_code)
            ->where('id', '!=', $appointment->id)
            ->get()
            ->map(function ($appointment) {
                return $appointment->update([
                    'reserved' => false,
                    'patient_id' => null,
                    'group_code' => ''
                ]);
            });

        $appointment->update(['status' => true, 'group_code' => '']);

        return new AppointmentResource($appointment);
    }

    public function rejectAppointment(ClinicRequest $request, $clinicId, $appointmentId)
    {
        $appointment = Appointment::find($appointmentId);

        $appointment->update([
            'patient_id' => null,
            'reserved' => false,
            'group_code' => ''
        ]);

        return new AppointmentsResource(Clinic::find($clinicId)->appointments);
    }

    public function getClinicsSpecialties(Request $request)
    {
        $clinicsIds = $request->get('clinicsIds', []);
        $specialties = app(ClinicMethods::class)->getClinicsSpecialties($clinicsIds);
        return ['results' => $specialties];
    }

    public function getSpecialtiesDoctors(Request $request)
    {
        $specialtiesId = $request->get('specialtiesId', []);
        $doctors = app(DoctorMethods::class)->getSpecialtiesDoctors(Specialties::find($specialtiesId));
        $doctorsArray = [];
        foreach ($doctors as $doctorId => $doctorName) {
            $doctorsArray[] = [
                'text' => $doctorName,
                'id' => $doctorId,
            ];
        }
        return ['results' => $doctorsArray];
    }
}
