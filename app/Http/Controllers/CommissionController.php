<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\CommissionPercentage;

class CommissionController extends Controller
{
  /***
   * Index all the orders
   */

   public function agent_view ()
   {
       return view('pages.agent.agent-data', [
           'agent' => Agent::class
       ]);
   }

   public function percentage_view ()
   {
       return view('pages.commission-percentage.commission-percentage-data', [
           'commissionPercentage' => CommissionPercentage::class
       ]);
   }
}
