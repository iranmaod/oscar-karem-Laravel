<?php

namespace App\Http\Livewire;

use App\Models\Agent;
use App\Models\CommissionPaymentType;
use App\Models\CommissionEmployeeType;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use HubSpot\Factory;
use HubSpot\Client\Crm\Contacts\ApiException;

class CreateAgent extends Component
{
    public $agent;
    public $agentId;
    public $hubspotId;
    public $action;
    public $button;
    public $percentages;
    public $paymentTypes;
    public $employeeTypes;
    protected $hubspot;

    protected function getRules()
    {
        $rules = ($this->action == "updateAgent") ? [


        ] : [

        ];

        return array_merge([
          'agent.hs_vid' => 'required',
          'agent.first_name' => 'required',
          'agent.last_name' => 'required',
          'agent.email' => 'required',
          'agent.user_id' => 'required',
          'agent.commission_payment_type' => 'required'
        ], $rules);
    }

    public function createAgent()
    {
        $this->resetErrorBag();
        $this->validate();


        Agent::create($this->agent);

        $this->emit('saved');
        $this->reset('agent');
    }




    public function updateAgent()
    {
        $this->resetErrorBag();
        $this->validate();

        Agent::query()
            ->where('id', base64_decode($this->agentId))
            ->update([
                "hs_vid" => $this->agent['hs_vid'],
                "first_name" => $this->agent['first_name'],
                "last_name" => $this->agent['last_name'],
                "email" => $this->agent['email'],
                "user_id" => $this->agent['user_id'],
                "commission_payment_type" => $this->agent['commission_payment_type'],

            ]);

        $this->emit('saved');
    }



    public function mount ()
    {

        if (!$this->agent && $this->agentId) {
            $agentId = base64_decode($this->agentId);
            $this->agent = Agent::find($agentId);
        }

        if($this->hubspotId){
          try {
            $hubspotId = base64_decode($this->hubspotId);
            $this->hubspot = Factory::createWithApiKey(base64_decode(env('HUBSPOT_API')));
            $response = $this->hubspot->crm()->contacts()->basicApi()->getPage(10, null, null, null, false);
            $propertyList = "firstname, lastname, email, company, city, phone, state, country, address";
            $owner = $this->hubspot->crm()->owners()->ownersApi()->getById($hubspotId, "id", false);

            $this->agent = array(
              'hs_vid' => $owner['id'],
              'first_name' => $owner['first_name'],
              'last_name'  => $owner['last_name'],
              'email' => $owner['email'],
              'user_id' => $owner['user_id'],
            );

          } catch (ApiException $e) {

          }
        }

        $this->paymentTypes = CommissionPaymentType::pluck('slug', 'name')->toArray();
        //$this->employeeTypes = CommissionEmployeeType::pluck('slug', 'name')->toArray();
        $this->button = create_button($this->action, "Agent");
    }



    public function render()
    {
        return view('livewire.create-agent');
    }
}
