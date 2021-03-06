<?php

namespace App\Http\Controllers\Backend\Auth\Role;

use App\ClinicNurse;
use App\Models\Auth\Lab;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Auth\Clinic;
use App\Models\Auth\Patient;
use App\PrivateDoctorRegistration;
use App\Reservations;
use Illuminate\Http\Request;
use App\Models\Auth\Appointment;
use App\Models\Auth\Specialties;
use App\Models\Auth\MedicalRecord;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentsResource;
use App\Events\Backend\Auth\Role\RoleDeleted;
use App\Repositories\Backend\Auth\RoleRepository;
use App\Http\Requests\Backend\Auth\Role\PatientRequest;
use App\Repositories\Backend\Auth\PermissionRepository;
use App\Http\Requests\Backend\Auth\Role\StoreRoleRequest;
use App\Http\Requests\Backend\Auth\Role\ManageRoleRequest;
use App\Http\Requests\Backend\Auth\Role\UpdateRoleRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class PatientController.
 */
class PatientController extends Controller
{
    public function __construct()
    {
    }

    public function index(PatientRequest $request)
    {
        if (true) {
            $patients = Patient::query()
                ->orderBy('id', 'asc')
                ->paginate(25);
        } elseif (isPatient()) {
            $user = \Auth::user();

            $patients = Patient::query()
                ->where('id', '=', $user->id)
                ->orderBy('id', 'asc')
                ->paginate(25);
        }


        return view('patient.index', compact('patients'));
    }

    public function create(PatientRequest $request)
    {
        return view('patient.create');
    }

    public function PatientReservationRecord(PatientRequest $request, Patient $patient)
    {
        $owner = \Auth::user();
        if ($owner->type == 'owner'){
            $clinicsIds = Clinic::query()
                ->select('id')
                ->where('owner_id',\Auth::user()->id)
                ->get();

            $labsIds = Lab::query()
                ->select('id')
                ->where('owner_id',\Auth::user()->id)
                ->get();

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

        }

//        DB::enableQueryLog();

        $patientClinics = Reservations::query()
            ->where('reservations.user_id',$patient->id)
            ->join('clinic_user','reservations.id','=','clinic_user.reservation_id')
            ->whereIn('clinic_user.clinic_id',$clinicsIds)
            ->join('clinics','clinics.id','=','clinic_user.clinic_id')
            ->select('reservations.id','clinics.name as name','reservations.appointment','reservations.created_at','reservations.type','reservations.status')
            ->get()->toArray();

//        logger(DB::getQueryLog());

        $patientLabs = Reservations::query()
            ->select('reservations.id','labs.name as name','reservations.appointment','reservations.created_at','reservations.type','reservations.status')
            ->where('reservations.user_id',$patient->id)
            ->join('lab_registrations','reservations.id','=','lab_registrations.reservation_id')
            ->join('labs','labs.id','=','lab_registrations.lab_id')
            ->whereIn('lab_registrations.lab_id',$labsIds)
            ->get()->toArray();


        $patientDoctors = Reservations::query()
            ->select('reservations.id','users.first_name as name','reservations.appointment','reservations.created_at','reservations.type','reservations.status')
            ->where('reservations.user_id',$patient->id)
            ->join('private_doctor_registrations','reservations.id','=','private_doctor_registrations.reservation_id')
            ->join('users','users.id','=','private_doctor_registrations.doctor_id')
            ->whereIn('private_doctor_registrations.doctor_id',$private_doctorsIds)
            ->get()->toArray();


        $patientNurses = Reservations::query()
            ->select('reservations.id','users.first_name as name','reservations.appointment','reservations.created_at','reservations.type','reservations.status')
            ->where('reservations.user_id',$patient->id)
            ->join('nurse_registrations','reservations.id','=','nurse_registrations.reservation_id')
            ->join('users','users.id','=','nurse_registrations.nurse_id')
            ->whereIn('nurse_registrations.nurse_id',$nurseIds)
            ->get()->toArray();


        $patientRecords = array_merge($patientClinics, $patientLabs,$patientDoctors,$patientNurses);


        return view('patient.patientReservationRecord', compact('patientRecords'));

    }

    public function show(PatientRequest $request, Patient $patient)
    {
        $reservations = Reservations::query()
        ->where('user_id',$patient->id)
            ->orderBy('id', 'asc')
            ->paginate(25);

        return view('patient.show', compact('reservations','patient'));
    }

    public function store(PatientRequest $request)
    {
        $patient = Patient::query()
            ->create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'password' => bcrypt($request->get('password')),
                'email' => $request->get('email'),
                'phone' => $request->get('phone'),
                'confirmation_code' => md5(uniqid(mt_rand(), true)),
                'confirmed' => true,
                'type' => 'patient'
            ]);

        $user = User::find($patient->id);
        $user->assignRole('administrator');


        return redirect()->route('admin.patient.index')->withFlashSuccess('The patient was successfully saved.');
    }

    /**
     * @param ManageRoleRequest $request
     * @param Role $role
     *
     * @return mixed
     */
    public function edit(PatientRequest $request, Patient $patient)
    {
        return view('patient.edit', compact('patient'));
    }


    public function update(PatientRequest $request, Patient $patient)
    {
        if (isCurrentUser($patient->id)) {
            $patient
                ->update([
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'password' => $request->get('password'),
                    'email' => $request->get('email'),
                    'phone' => $request->get('phone'),
                    'confirmation_code' => md5(uniqid(mt_rand(), true)),
                    'confirmed' => true,
                    'type' => 'patient'
                ]);
        } else {
            $patient
                ->update([
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'email' => $request->get('email'),
                    'phone' => $request->get('phone'),
                    'confirmation_code' => md5(uniqid(mt_rand(), true)),
                    'confirmed' => true,
                    'type' => 'patient'
                ]);
        }

        $clinics = array_values($request->get('clinics') ?? []);
        $patient->clinics()->sync($clinics);

        return redirect()->route('admin.patient.index')->withFlashSuccess('The patient was successfully updated.');
    }

    /**
     * @param PatientRequest $request
     * @param Patient $patient
     * @return mixed
     * @throws \Exception
     */
    public function destroy(PatientRequest $request, Patient $patient)
    {
        $patient->delete();

        return redirect()->route('admin.patient.index')->withFlashSuccess('The patient was successfully deleted.');
    }

    public function reserve(Request $request)
    {
        $clinics = Clinic::whereIn('id', $request->get('clinic_ids'));

        foreach ($clinics as $clinic) {
            $appointment = Appointment::find($clinic->appointments[0]);


            if (! \Auth::user()->medicalRecord) {
                $medicalRecord = new MedicalRecord();
                $medicalRecord->user_id = \Auth::user()->id;

                \Auth::user()->medicalRecord()->save($medicalRecord);
            }

            if ($appointment && ! $appointment->reserved) {
                $appointment->update([
                    'reserved' => true,
                    'patient_id' => \Auth::user()->id,
                    'status' => 0,
                    'group_code' => \Auth::user()->id.'#'.$clinics->pluck('id')

                ]);
            }
        }
    }

    public function getAppointments(PatientRequest $request, $patientId)
    {
        return new AppointmentsResource(User::find($patientId)->myAppointments);
    }

    public function anyAppointment(PatientRequest $request)
    {
        $appointments = [];

        if ($request->get('start_at') && $request->get('specialty')) {
            $clinics = Specialties::find($request->get('specialty'))
                ->clinics
                ->map(function ($clinic) use ($request, &$appointments) {
                    array_push($appointments, $clinic->appointments()
                        ->available()
                        ->where('start_at', $request->get('start_at'))
                        ->get());
                });

            foreach (array_flatten($appointments, 1) as $appointment) {
                $appointment->update([
                    'status' => 0,
                    'reserved' => true,
                    'patient_id' => \Auth::user()->id,
                    'group_code' => \Auth::user()->id.'#'.$request->get('specialty')
                ]);
            }
        }
    }

    public function search(Request $request)
    {
        $appointments= $this->loadQuery($request)
            ->get();

        return $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->clinic->id,
                'name' =>$appointment->clinic->name
            ];
        });
    }

    public function loadQuery($request)
    {
        // Allow custom params from request
        $permittedParams = ['doctor_id', 'service_type', 'clinic_id', 'start_at'];

        $query = Appointment::query()->where('reserved', false);

        foreach ($permittedParams as $param) {
            if ($value = $request->get($param)) {
                $query->where($param, $value);
            }
        }

        if ($request->get('range')) {
            return $query->whereBetween('start_at', $request->get('range'));
        }

        return $query;
    }
}
