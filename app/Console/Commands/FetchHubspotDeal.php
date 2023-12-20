<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use HubSpot\Client\Crm\Deals\Model\Filter as DealFilter;
use HubSpot\Client\Crm\Deals\Model\FilterGroup as DealFilterGroup;
use HubSpot\Client\Crm\Deals\Model\PublicObjectSearchRequest as DealSearchRequest;
use HubSpot\Client\Crm\Deals\Model\SimplePublicObjectInput as DealInput;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Deal;
use App\Models\DealInstallment;
use HubSpot\Factory;

class FetchHubspotDeal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deal:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to fetch deals from hubspot';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->hubspot = Factory::createWithApiKey(base64_decode(env('HUBSPOT_API')));
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

            $deals = array();
            $agentDeals = array();
            $agentTotalSales = array();
            $agentTotalCommission = array();
            $filter_date_type = request('filter_date_type');
            $filter_operator = request('filter_operator');
            $filter_date = request('filter_date');
            $filter_query = request('filter_query');

            $defaultStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
            $defaultEndDate = Carbon::now()->endOfMonth()->format('Y-m-d');
            $afterRecord = Deal::orderBy('closedate', 'desc')->first();
            $after =  (@$afterRecord->closedate) ? $afterRecord->closedate : false;


            $dealProperties = $this->hubspot->crm()->properties()->coreApi()->getAll("deals", false);
            $dealPropertiesResult = $dealProperties->getResults();


            if($filter_operator && $filter_date_type && $filter_date){
              $hsDateFilter = new DealFilter();
              $hsDateFilter->setOperator($filter_operator);
              $hsDateFilter->setPropertyName($filter_date_type);
              $hsDateFilter->setValue(Carbon::createFromFormat('Y-m-d', $filter_date)->timestamp * 1000);
            } else {
              $hsDateFilter = false;
            }


            $dealFilter = new DealFilter();
            $dealFilter->setOperator('EQ');
            $dealFilter->setPropertyName('dealstage');
            $dealFilter->setValue("6606107");

            $deal2Filter = new DealFilter();
            $deal2Filter->setOperator('EQ');
            $deal2Filter->setPropertyName('dealstage');
            $deal2Filter->setValue("presentationscheduled");


            $apiResponse1 = $this->searchHubspotDeals($dealFilter, $deal2Filter, $hsDateFilter, $after, false);
            $deals = array_merge($agentDeals,  $apiResponse1->getResults());


          foreach ($deals as $key => $deal) {
              $properties = $deal['properties'];
              $setter = $properties['set_up_caller'] ?? null;
              $closer = $properties['hubspot_owner_id'] ?? null;
              $deal_stage = $properties['dealstage'] ?? null;
              $setter_deal_number = 0;
              $closer_deal_number = 0;
              $number_of_installments = $properties['wie_viele_ratenzahlungen_'] ?? 1;

              $is_individual = $deal_stage == '6606107';
              if($properties['closedate']){

                $close_date = Carbon::parse($properties['closedate']);
                $setter_deal_number = Deal::whereBetween('closedate', [$close_date->startOfMonth(), $close_date->endOfMonth()])->where(function($query) use ($setter) {
                  $query->where('set_up_caller', $setter)->orWhere('hubspot_owner_id', $setter);
                })->count();
                $closer_deal_number = Deal::whereBetween('closedate', [$close_date->startOfMonth(), $close_date->endOfMonth()])->where(function($query) use ($closer) {
                  $query->where('set_up_caller', $closer)->orWhere('hubspot_owner_id', $closer);
                })->count();


              }
              $uniqueKey = array(
                "hs_object_id" => $properties['hs_object_id'] ?? null,
              );

              $data = array(
                "hs_object_id" => $properties['hs_object_id'] ?? null,
                "produkt" => $properties['produkt'] ?? null,
                "createdate" => $properties['createdate'] ? Carbon::parse($properties['createdate'])->format('Y-m-d H:i:s'): null,
                "closedate" => $properties['closedate'] ? Carbon::parse($properties['closedate'])->format('Y-m-d H:i:s') : null,
                "amount" => $properties['hs_closed_amount_in_home_currency'] && is_numeric($properties['hs_closed_amount_in_home_currency']) ? $properties['hs_closed_amount_in_home_currency'] : 0,
                "comission" => $properties['comission'] ?? null,
                "dealname" => $properties['dealname'] ?? null,
                "set_up_caller" => $properties['set_up_caller'] ?? null,
                "number_of_installments" => $number_of_installments,
                "dealstage" => $deal_stage,
                "set_up_caller" => $setter,
                "hubspot_owner_id" => $closer,
                "order_status" => $properties['order_status'] ?? null,
                "paid_installments" => $properties['paid_installments'] ?? null,
                "is_individual" => $is_individual,
                "setter_deal_number" => 1,
                "closer_deal_number" => 1,
              );

              try {
                  Deal::updateOrCreate($uniqueKey, $data);
                  DealInstallment::updateOrCreate(
                  array(
                    'deal_id' => $properties['hs_object_id'],
                  ),
                  array(
                  'order_id' => null,
                  'deal_id' => $properties['hs_object_id'] ?? null,
                  'paid_date' => $properties['closedate'] ? Carbon::parse($properties['closedate'])->format('Y-m-d H:i:s') : null,
                  'current_count' => 1,
                  'total_count' => is_numeric($properties['wie_viele_ratenzahlungen_']) ? $properties['wie_viele_ratenzahlungen_'] : 1,
                  'payment_id' => null
                ));
              } catch (\Illuminate\Database\QueryException $exception) {
                  // You can check get the details of the error using `errorInfo`:
                  //$errorInfo = $exception->errorInfo;

                  // Return the response to the client..
              }



            }



        return 0;
    }


    /*** Search Hubspot Deals ***/

    protected function searchHubspotDeals($dealFilter, $deal2Filter, $hsDateFilter, $after = false, $query = false){
      try {

          $filterGroups = array();
          $query = request('filter_query');
          $limit = "100";


          $sort = [[
            'propertyName' => 'closedate',
            'direction' => 'ASCENDING',
          ]];

          $properties = array(
            "produkt",
            "art_des_deals",
            "pipeline",
            "closedate",
            "amount",
            "comission",
            "dealname",
            "set_up_caller",
            "dealtype",
            "hs_closed_amount_in_home_currency",
            "wie_viele_ratenzahlungen_",
            "dealstage",
            "von_welcher_kampagne_",
            "set_up_caller",
            "hubspot_owner_id",
            "hs_createdate"
          );

          $closedateFilter = new DealFilter();
          $closedateFilter->setOperator('HAS_PROPERTY');
          $closedateFilter->setPropertyName('closedate');

          $group1 = new DealFilterGroup();
          $group2 = new DealFilterGroup();

          $group1Filters = [$dealFilter, $closedateFilter];
          $group2Filters = [$deal2Filter, $closedateFilter];

          if($hsDateFilter){
            $group1Filters[] = $hsDateFilter;
            $group2Filters[] = $hsDateFilter;
          }

          if($after){
              $after = Carbon::createFromFormat('Y-m-d H:i:s', $after)->timestamp * 1000;
              $afterFilter = new DealFilter();
              $afterFilter->setOperator('GT');
              $afterFilter->setPropertyName('closedate');
              $afterFilter->setValue($after);
              $group1Filters[] = $afterFilter;
              $group2Filters[] = $afterFilter;
          }

          $group1->setFilters($group1Filters);
          $group2->setFilters($group2Filters);

          $dealSearchRequest = new DealSearchRequest();
          $dealSearchRequest->setFilterGroups([$group1, $group2]);

          // if($filterGroup1 || $filterGroup2 || $after){
          //   $dealSearchRequest->setFilterGroups($filterGroups);
          // }

          if($query && $query != ""){
            $dealSearchRequest->setQuery($query);
          }


          $dealSearchRequest->setProperties($properties);
          $dealSearchRequest->setLimit($limit);
          $dealSearchRequest->setSorts($sort);
          $dealKey = $after ? 'dealResponseAfter' : 'dealResponse';
          $apiResponse = Cache::remember('deal-'.$after.'-'.$query, 1 , function () use ($dealSearchRequest) {
                return   $apiResponse = $this->hubspot->crm()->deals()->searchApi()->doSearch($dealSearchRequest);
          });
          return $apiResponse;

      } catch (DealApiException $e) {
          dd($e);
      }

    }
}
