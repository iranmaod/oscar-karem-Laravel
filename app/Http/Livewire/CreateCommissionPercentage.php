<?php

namespace App\Http\Livewire;

use App\Models\CommissionPercentage;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use App\Models\Status;
use App\Models\CommissionPaymentType;
use App\Models\CommissionLead;
use App\Models\CommissionEmployeeType;



class CreateCommissionPercentage extends Component
{
    public $commissionPercentage;
    public $commissionPercentageId;
    public $hubspotId;
    public $action;
    public $button;
    public $employeeTypes;
    public $leadTypes;
    public $paymentTypes;
    public $statuses;


    protected function getRules()
    {
        $rules = ($this->action == "updateCommissionPercentage") ? [


        ] : [

        ];

        return array_merge([
          'commissionPercentage.name' => 'required',
          'commissionPercentage.slug' => 'required',
          'commissionPercentage.commission_employee_type' => 'required',
          'commissionPercentage.commission_lead' => 'required',
          'commissionPercentage.commission_payment_type' => 'required',
          'commissionPercentage.first_lead' => 'required',
          'commissionPercentage.second_lead' => 'required',
          'commissionPercentage.third_lead' => 'required',
          'commissionPercentage.fourth_lead' => 'required',
          'commissionPercentage.fifth_lead' => 'required',
          'commissionPercentage.onward_lead' => 'required',
          'commissionPercentage.hs_deal_name' => 'required',
          'commissionPercentage.status_id' => 'required'
        ], $rules);
    }

    public function createCommissionPercentage()
    {
        $this->resetErrorBag();
        $this->validate();


        CommissionPercentage::create($this->commissionPercentage);

        $this->emit('saved');
        $this->reset('commissionPercentage');
    }




    public function updateCommissionPercentage()
    {
        $this->resetErrorBag();
        $this->validate();

        CommissionPercentage::query()
            ->where('id', base64_decode($this->commissionPercentageId))
            ->update([
                "name" => $this->commissionPercentage['name'],
                "slug" => $this->commissionPercentage['slug'],
                "commission_employee_type" => $this->commissionPercentage['commission_employee_type'],
                "commission_lead" => $this->commissionPercentage['commission_lead'],
                "commission_payment_type" => $this->commissionPercentage['commission_payment_type'],
                "first_lead" => $this->commissionPercentage['first_lead'],
                "second_lead" => $this->commissionPercentage['second_lead'],
                "third_lead" => $this->commissionPercentage['third_lead'],
                "fourth_lead" => $this->commissionPercentage['fourth_lead'],
                "fifth_lead" => $this->commissionPercentage['fifth_lead'],
                "onward_lead" => $this->commissionPercentage['onward_lead'],
                "hs_deal_name" => $this->commissionPercentage['hs_deal_name'],
                "status_id" => $this->commissionPercentage['status_id']
            ]);

        $this->emit('saved');
    }



    public function mount ()
    {

        if (!$this->commissionPercentage && $this->commissionPercentageId) {
            $commissionPercentageId = base64_decode($this->commissionPercentageId);
            $this->commissionPercentage = CommissionPercentage::find($commissionPercentageId);
        }

        $this->employeeTypes = CommissionEmployeeType::pluck('slug', 'name')->toArray();
        $this->leadTypes =  CommissionLead::pluck('slug', 'name')->toArray();
        $this->paymentTypes = CommissionPaymentType::pluck('slug', 'name')->toArray();
        $this->statuses = Status::pluck('id', 'name')->toArray();

        $this->button = create_button($this->action, "CommissionPercentage");
    }



    public function render()
    {
        return view('livewire.create-commission-percentage');
    }
}
