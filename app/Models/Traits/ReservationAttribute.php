<?php

namespace App\Models\Traits;

use App\Methods\GeneralMethods;

/**
 * Trait PrivateDoctorAttribute.
 */
trait ReservationAttribute
{
    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="' . route('admin.private-doctor.create', ['privatedoctor' => $this->id]) . '" class="btn btn-primary"><i class="fas fa-edit" data-toggle="tooltip" data-placement="top" title="' . __('buttons.general.crud.edit') . '"></i></a>';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        return '<a href="' . route('admin.doctor.destroy', $this) . '"
			 data-method="delete"
			 data-trans-button-cancel="' . __('buttons.general.cancel') . '"
			 data-trans-button-confirm="' . __('buttons.general.crud.delete') . '"
			 data-trans-title="' . __('strings.backend.general.are_you_sure') . '"
			 class="btn btn-danger"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="' . __('buttons.general.crud.delete') . '"></i></a> ';
    }

    /**
     * @return string
     */
    public function getShowButtonAttribute()
    {
        return '<a href="' . route('admin.reservation.edit', ['reservation' => $this->id]) . '" data-toggle="tooltip" data-placement="top" title="' . __('buttons.general.crud.view') . '" class="btn btn-success change_status_button"><i class="fas fa-eye"></i></a>';
    }


    /**
     * @return string
     */
    public function getApproveButtonAttribute()
    {
        return '<a href="' . route('admin.reservation.confirmPaymentOwner', ['reservation_id' => $this->id]) . '" data-toggle="tooltip" data-placement="top" title="' . __('buttons.general.crud.edit') . '" class="btn btn-warning change_status_button">Require confirm reservation</a>';
    }


    /**
     * @return string
     */
    public function getRejectButtonAttribute()
    {
        return '<a href="' . route('admin.private-doctor.reject', $this) . '" data-toggle="tooltip" data-placement="top" title="' . 'Reject' . '" class="btn btn-warning change_status_button"><i class="fa fa-ban"></i></a>';
    }


    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        $state = '';
        if ($this->status == "require-time"){
            $state = $this->show_button;
        }

        else if ($this->status == "require-confirm-owner"){
            $state = $this->approve_button;
        }
//        else if($this->status == "request-remove"){
//
//            $acceptRemove ='<a href="' . route('admin.reservation.destroy', ['reservation_id' => $this->id]) . '" class="btn btn-danger"><i title="require-time"></i>Accept Remove</a>';
//            $rejectRemove ='<a href="' . route('admin.reservation.destroy', ['reservation_id' => $this->id]) . '" class="btn btn-warning"><i title="require-time"></i>Reject Remove</a>';
//
/*            _token: '<?php echo e(csrf_token()); ?>',*/
//
//            $state = $rejectRemove.$acceptRemove;
//
//        }

        return '
    	<div class="btn-group" role="group" aria-label="' . __('labels.backend.access.users.user_actions') . '">
		  ' . $state . '
		
		</div>';
    }


    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->last_name
            ? $this->first_name . ' ' . $this->last_name
            : $this->first_name;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->full_name;
    }


}
