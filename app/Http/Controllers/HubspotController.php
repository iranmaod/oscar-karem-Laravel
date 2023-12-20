<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Installment;
use App\Models\VatPercentage;
use App\Models\Agent;
use App\Models\Order;
use App\Models\Deal;
use App\Models\DealInstallment;
use Artisan;
use HubSpot\Factory;
use HubSpot\Client\Crm\Contacts\ApiException as ContactApiException;
use HubSpot\Client\Crm\Owners\ApiException as OwnerApiException;
use HubSpot\Client\Crm\Deals\ApiException as DealApiException;

use HubSpot\Client\Crm\Contacts\Model\Filter as ContactFilter;
use HubSpot\Client\Crm\Contacts\Model\FilterGroup as ContactFilterGroup;
use HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest as ContactSearchRequest;
use HubSpot\Client\Crm\Deals\Model\Filter as DealFilter;
use HubSpot\Client\Crm\Deals\Model\FilterGroup as DealFilterGroup;
use HubSpot\Client\Crm\Deals\Model\PublicObjectSearchRequest as DealSearchRequest;
use HubSpot\Client\Crm\Deals\Model\SimplePublicObjectInput as DealInput;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use function _\filter as LodashFilter;
use function _\reduce as LodashReduce;
use HubSpot\Client\Crm\Associations\ApiException;

use HubSpot\Client\Crm\Properties\ApiException as PropertyApiException;
use Illuminate\Support\Facades\Http;

class HubspotController extends Controller
{

  public $hubspot;
  /**
   * Instantiate a new UserController instance.
   */
  public function __construct()
  {

    if(env('HS_MODE') == "development"){
      $this->hubspot = Factory::createWithAccessToken(base64_decode(env('HUBSPOT_PAT')));
    } else {
      $this->hubspot = Factory::createWithApiKey(base64_decode(env('HUBSPOT_API')));
    }

  }

  /*** List all the contacts of Hubspot under Contacts > All Contacts ***/

  public function contact_view ()
  {


    $response =  $this->searchHubspotContacts();
    // $propertiesLabel = array();
    // $properties = $this->hubspot->crm()->properties()->coreApi()->getAll("Deal", false);
    //
    // foreach($properties->getResults() as $contactProp){
    //   $propertiesLabel[$contactProp['name']] = $contactProp['label'];
    // }
    // dd($propertiesLabel);



    $nextPage = $response->getPaging()['next']['after'] ?? null;
    $after = '';

    $contacts = array();
    foreach ($response->getResults()  as $key => $contact) {
        array_push($contacts, array(
          'vid' => $contact->getId(),
          'firstname' => $contact->getProperties()['firstname'] ?? null,
          'lastname'  => $contact->getProperties()['lastname'] ?? null,
          'email' => $contact->getProperties()['email'] ?? null,
          'phone' => $contact->getProperties()['phone'] ?? null,
          'hs_phone_number' => $contact->getProperties()['hs_calculated_phone_number'] ?? null,
          'country' => $contact->getProperties()['country'] ?? null,
          'address' => $contact->getProperties()['address'] ?? null,
          'phone_number' => $contact->getProperties()['phone_number'] ?? null,
        ));
        $nextPage = $contact->getId();
      }
      $contacts = (object)($contacts);

      return view('pages.contact.contact-data', compact('contacts', 'nextPage'));
  }


  /*** List all the contacts of Hubspot under Sales Agents > Hubspot Owner Lists ***/

  public function list_agent_view(){
    $agents = Agent::pluck('hs_vid')->toArray();

    try {

      $response = Cache::remember('owner', 3600, function(){
            return $this->hubspot->crm()->owners()->ownersApi()->getPage(null, null, 100, false);
      });

      $owners = array();
      foreach ($response->getResults()  as $key => $owner) {
          array_push($owners, array(
            'id' => $owner['id'],
            'first_name' => $owner['first_name'],
            'last_name'  => $owner['last_name'],
            'email' => $owner['email'],
            'user_id' => $owner['user_id'],
            'generated' => in_array($owner['id'], $agents)
          ));
      }

    } catch (OwnerApiException $e) {
        echo "Exception when calling basic_api->get_page: ", $e->getMessage();
    }

    $owners = (object)($owners);

    return view('pages.agent.agent-list', compact('owners'));
  }


  /*** Retrieve a form to display Contact based order form Order ***/

  public function contact_order ($contactId)
  {
    $contactId = base64_decode($contactId);
    $elopage_api_key =  base64_decode(env('ELOPAGE_API_KEY'));
    $elopage_api_secret = base64_decode(env('ELOPAGE_API_SECRET'));

    try {
      $elopage_response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Second' => 'bar'
      ])->get("https://api.elopage.com/api/products?key=".$elopage_api_key."&secret=".$elopage_api_secret);
      $products = $elopage_response->json();

      $propertyList = "firstname, lastname, email, company, city, phone, state, country, address";
      $hubspot_response = $this->hubspot->crm()->contacts()->basicApi()->getById($contactId, $propertyList, null, false, null);
      $properties = $hubspot_response->getProperties();

      $installments = Installment::where('status_id', 3)->get();
      $vat_percentages = VatPercentage::where('status_id', 3)->get();
      $contact = array(
        'vid' =>  $properties['hs_object_id'] ?? null,
        'firstname' => $properties['firstname'] ?? null,
        'lastname' => $properties['lastname'] ?? null,
        'email' => $properties['email'] ?? null,
        'company' => $properties['company'] ?? null,
        'city' => $properties['city'] ?? null,
        'phone' => $properties['phone'] ?? null,
        'state' => $properties['state'] ?? null,
        'country' => $properties['country'] ?? null,
        'address' => $properties['address'] ?? null
      );
      return view('pages.contact.contact-order', compact('products', 'installments', 'contact', 'vat_percentages'));

    } catch (ApiException $e) {
        echo "Exception when calling basic_api->get_page: ", $e->getMessage();
    }

  }


  public function elopage_product(){
    $elopage_api_key =  base64_decode(env('ELOPAGE_API_KEY'));
    $elopage_api_secret = base64_decode(env('ELOPAGE_API_SECRET'));

    $elopage_response = Http::withHeaders([
          'Content-Type' => 'application/json',
          'X-Second' => 'bar'
    ])->get("https://api.elopage.com/api/products?key=".$elopage_api_key."&secret=".$elopage_api_secret);
    $products = $elopage_response->json();
  }

/*** List all the contacts of Hubspot under Commission > Commission Overview ***/

public function commission_overview(){

  $filter_query = request('filter_query');
  $filter_product = request('filter_product');
  $filter_date_type = "closedate";
  $filter_start_date = request('start_date') ? request('start_date') : Carbon::now()->startOfMonth()->format('Y-m-d');
  $start_date = $filter_start_date ? Carbon::createFromFormat('Y-m-d', $filter_start_date) : Carbon::now()->startOfMonth();
  $filter_end_date = request('end_date') ? request('end_date') : Carbon::now()->format('Y-m-d');
  $end_date = $filter_end_date ? Carbon::createFromFormat('Y-m-d', $filter_end_date) : Carbon::now();


  if($filter_date_type == "closedate" || $filter_date_type == "createdate"){

    $deals = Deal::whereBetween($filter_date_type, [$filter_start_date, $filter_end_date]);

    if($filter_query && $filter_query != ""){
      $deals->where('dealname', 'LIKE', '%' . $filter_query . '%');
    }

    if($filter_product && $filter_product != ""){
      $deals->where('produkt', $filter_product);
    }

    $deals = $deals->get();
  }


  if($filter_date_type = "installment_paid_date"){
    $deals = DealInstallment::with('deal')->whereBetween('paid_date', [$filter_start_date, $filter_end_date]);

    if($filter_query && $filter_query != ""){
      $deals->whereHas('deal', function ($query) use ($filter_query) {
        return $query->where('dealname', 'LIKE', '%' . $filter_query . '%');
      });
    }

    if($filter_product && $filter_product != ""){
      $deals->whereHas('deal', function ($query) use ($filter_product)  {
        return $query->where('produkt', $filter_product);
      });
    }

    $deals = $deals->get()->pluck('deal')->flatten();
  }


  $products = $deals->pluck('produkt')->toArray();
  $products = array_filter(array_unique($products));
  $agents = Agent::get();
  $agentDeals = array();
  $object = false;

  foreach($agents as $agent){
      $agentDealData = LodashFilter($deals, function($o) use ($agent){
      return $o['hubspot_owner_id'] == $agent->hs_vid || $o["set_up_caller"] == $agent->hs_vid;
    });

    $agentDeals[$agent->hs_vid] = collect($agentDealData)->sortBy('closedate')->all();

  }


  return view('pages.commission.commission-data', compact('agents', 'agentDeals', 'filter_date_type', 'filter_start_date', 'filter_end_date', 'filter_query', 'filter_product', 'products'));
}

/*** Fetch Hubspot Deals and store them in Database. Moved to command FetchHubspotDeal ***/
public function fetch_hs_deals(){

      Artisan::call('deal:fetch');

      // $deals = array();
      // $agentDeals = array();
      // $agentTotalSales = array();
      // $agentTotalCommission = array();
      // $filter_date_type = request('filter_date_type');
      // $filter_operator = request('filter_operator');
      // $filter_date = request('filter_date');
      // $filter_query = request('filter_query');
      //
      // $defaultStartDate = Carbon::now()->startOfMonth()->format('Y-m-d');
      // $defaultEndDate = Carbon::now()->endOfMonth()->format('Y-m-d');
      // $afterRecord = Deal::orderBy('closedate', 'desc')->first();
      // $after =  (@$afterRecord->closedate) ? $afterRecord->closedate : false;
      // //dd($after);
      //
      //
      // $dealProperties = $this->hubspot->crm()->properties()->coreApi()->getAll("deals", false);
      // $dealPropertiesResult = $dealProperties->getResults();
      // // $dealPropertiesKey = array();
      // // foreach($dealPropertiesResult as $dealProperty){
      // //   $dealPropertiesKey[$dealProperty['name']] = $dealProperty['label'];
      // // }
      // //
      // // dd($dealPropertiesKey);
      //
      // if($filter_operator && $filter_date_type && $filter_date){
      //   $hsDateFilter = new DealFilter();
      //   $hsDateFilter->setOperator($filter_operator);
      //   $hsDateFilter->setPropertyName($filter_date_type);
      //   $hsDateFilter->setValue(Carbon::createFromFormat('Y-m-d', $filter_date)->timestamp * 1000);
      // } else {
      //   $hsDateFilter = false;
      // }
      //
      //
      //
      // // $ownerFilter = new Filter();
      // // $ownerFilter->setOperator('EQ');
      // // $ownerFilter->setPropertyName('hubspot_owner_id');
      // // $ownerFilter->setValue($agent->hs_vid);
      // //
      // // $setterFilter = new Filter();
      // // $setterFilter->setOperator('EQ');
      // // $setterFilter->setPropertyName('set_up_caller');
      // // $setterFilter->setValue($agent->hs_vid);
      //
      // // $afterFilter = new DealFilter();
      // // $afterFilter->setOperator('LT');
      // // $afterFilter->setPropertyName('hs_object_id');
      // // $afterFilter->setValue($after);
      //
      // $dealFilter = new DealFilter();
      // $dealFilter->setOperator('EQ');
      // $dealFilter->setPropertyName('dealstage');
      // $dealFilter->setValue("6606107");
      //
      // $deal2Filter = new DealFilter();
      // $deal2Filter->setOperator('EQ');
      // $deal2Filter->setPropertyName('dealstage');
      // $deal2Filter->setValue("presentationscheduled");
      //
      //
      // $apiResponse1 = $this->searchHubspotDeals($dealFilter, $deal2Filter, $hsDateFilter, $after, false);
      // $deals = array_merge($agentDeals,  $apiResponse1->getResults());
      //
      //
      // foreach ($deals as $key => $deal) {
      //   $properties = $deal['properties'];
      //   $setter = $properties['set_up_caller'] ?? null;
      //   $closer = $properties['hubspot_owner_id'] ?? null;
      //   $setter_deal_number = 0;
      //   $closer_deal_number = 0;
      //
      //   $is_individual = $setter == $closer ? 1 : 0;
      //   if($properties['closedate']){
      //
      //     $close_date = Carbon::parse($properties['closedate']);
      //     $setter_deal_number = Deal::whereBetween('closedate', [$close_date->startOfMonth(), $close_date->endOfMonth()])->where(function($query) use ($setter) {
      //       $query->where('set_up_caller', $setter)->orWhere('hubspot_owner_id', $setter);
      //     })->count();
      //     $closer_deal_number = Deal::whereBetween('closedate', [$close_date->startOfMonth(), $close_date->endOfMonth()])->where(function($query) use ($closer) {
      //       $query->where('set_up_caller', $closer)->orWhere('hubspot_owner_id', $closer);
      //     })->count();
      //
      //
      //
      //     // $setter_deal_number = Deal::whereBetween($filter_date_type, [$close_date->startOfMonth(), $close_date->endOfMonth()])->where(function($query) use ($setter) {
      //     //   $query->where('set_up_caller', $setter)->orWhere('hubspot_owner_id', $setter);
      //     // })->count();
      //     // dd($abc);
      //
      //   }
      //   $uniqueKey = array(
      //     "hs_object_id" => $properties['hs_object_id'] ?? null,
      //   );
      //   $data = array(
      //     "hs_object_id" => $properties['hs_object_id'] ?? null,
      //     "produkt" => $properties['produkt'] ?? null,
      //     "createdate" => $properties['createdate'] ? Carbon::parse($properties['createdate'])->format('Y-m-d H:i:s'): null,
      //     "closedate" => $properties['closedate'] ? Carbon::parse($properties['closedate'])->format('Y-m-d H:i:s') : null,
      //     "amount" => $properties['hs_closed_amount_in_home_currency'] && is_numeric($properties['hs_closed_amount_in_home_currency']) ? $properties['hs_closed_amount_in_home_currency'] : 0,
      //     "comission" => $properties['comission'] ?? null,
      //     "dealname" => $properties['dealname'] ?? null,
      //     "set_up_caller" => $properties['set_up_caller'] ?? null,
      //     "number_of_installments" => $properties['wie_viele_ratenzahlungen_'] ?? null,
      //     "dealstage" => $properties['dealstage'] ?? null,
      //     "set_up_caller" => $setter,
      //     "hubspot_owner_id" => $closer,
      //     "order_status" => $properties['order_status'] ?? null,
      //     "paid_installments" => $properties['paid_installments'] ?? null,
      //     "is_individual" => $is_individual,
      //     "setter_deal_number" => 1,
      //     "closer_deal_number" => 1,
      //   );
      //
      //   Deal::updateOrCreate($uniqueKey, $data);
      // }








    // foreach($agents as $agent){
    //   $agentDeals[$agent->hs_vid] = LodashFilter($deals, function($o) use ($agent){ return $o['properties']['hubspot_owner_id'] == $agent->hs_vid || $o["properties"]["set_up_caller"] == $agent->hs_vid; });
    // }

    //return view('pages.commission.commission-data', compact('agents', 'agentDeals', 'filter_date_type', 'filter_operator', 'filter_date', 'filter_query'));
}




  /*** Search Hubspot Deals ***/

  protected function searchHubspotDeals($dealFilter, $deal2Filter, $hsDateFilter, $after = false, $query = false){
    // try {
    //
    //     $filterGroups = array();
    //     $query = request('filter_query');
    //     $limit = "100";
    //
    //
    //     $sort = [[
    //       'propertyName' => 'closedate',
    //       'direction' => 'ASCENDING',
    //     ]];
    //
    //
    //
    //
    //     $properties = array(
    //       "produkt",
    //       "hs_analytics_source",
    //       "hs_analytics_source_data_1",
    //       "hs_analytics_source_data_2",
    //       "art_des_deals",
    //       "hs_campaign",
    //       "pipeline",
    //       "closedate",
    //       "amount",
    //       "comission",
    //       "dealname",
    //       "set_up_caller",
    //       "dealtype",
    //       "hs_closed_amount_in_home_currency",
    //       "wie_viele_ratenzahlungen_",
    //       "dealstage",
    //       "von_welcher_kampagne_",
    //       "hs_all_owner_ids",
    //       "set_up_caller",
    //       "hubspot_owner_id",
    //       "hs_createdate"
    //     );
    //
    //     // if($filterGroup1){
    //     //   $filterGroups[] = $filterGroup1;
    //     // }
    //     //
    //     // if($filterGroup2){
    //     //   $filterGroups[] = $filterGroup2;
    //     // }
    //
    //     $closedateFilter = new DealFilter();
    //     $closedateFilter->setOperator('HAS_PROPERTY');
    //     $closedateFilter->setPropertyName('closedate');
    //     //$closedateFilter->setValue("null");
    //
    //
    //
    //     $group1 = new DealFilterGroup();
    //     $group2 = new DealFilterGroup();
    //
    //     $group1Filters = [$dealFilter, $closedateFilter];
    //     $group2Filters = [$deal2Filter, $closedateFilter];
    //
    //     if($hsDateFilter){
    //       $group1Filters[] = $hsDateFilter;
    //       $group2Filters[] = $hsDateFilter;
    //     }
    //
    //     if ($after) {
    //           $after = Carbon::createFromFormat('Y-m-d H:i:s', $after)->timestamp * 1000;
    //           $afterFilter = new DealFilter();
    //           $afterFilter->setOperator('GT');
    //           $afterFilter->setPropertyName('closedate');
    //           $afterFilter->setValue($after);
    //           $group1Filters[] = $afterFilter;
    //           $group2Filters[] = $afterFilter;
    //       }
    //
    //       $group1->setFilters($group1Filters);
    //       $group2->setFilters($group2Filters);
    //
    //     $dealSearchRequest = new DealSearchRequest();
    //     $dealSearchRequest->setFilterGroups([$group1, $group2]);
    //
    //     // if($filterGroup1 || $filterGroup2 || $after){
    //     //   $dealSearchRequest->setFilterGroups($filterGroups);
    //     // }
    //
    //     if($query && $query != ""){
    //       $dealSearchRequest->setQuery($query);
    //     }
    //
    //
    //     $dealSearchRequest->setProperties($properties);
    //     $dealSearchRequest->setLimit($limit);
    //     $dealSearchRequest->setSorts($sort);
    //     $dealKey = $after ? 'dealResponseAfter' : 'dealResponse';
    //     $apiResponse = Cache::remember('deal-'.$after.'-'.$query, 1 , function () use ($dealSearchRequest) {
    //           return   $apiResponse = $this->hubspot->crm()->deals()->searchApi()->doSearch($dealSearchRequest);
    //     });
    //     //dd($apiResponse->getResults());
    //     return $apiResponse;
    //
    // } catch (DealApiException $e) {
    //     dd($e);
    // }

  }

  /*** Search hubspot contacts ***/

  protected function searchHubspotContacts(){

    $query = request('query');
    $properties = array(
      "createdate",
      "email",
      "firstname",
      "hs_object_id",
      "lastmodifieddate",
      "lastname",
      "phone",
      "hs_calculated_mobile_number",
      "hs_calculated_phone_number",
      "hs_calculated_phone_number_area_code",
      "hs_calculated_phone_number_country_code",
      "hs_calculated_phone_number_region_code",
      "phone_number",
      "country",
      "address"
  );


    try {
      $filterGroups = array();
      $limit = 20;
      $after = request('after')? request('after') : null;
      $sort = [[
        'propertyName' => 'hs_object_id',
        'direction' => 'DESCENDING',
      ]];

      $contactSearchRequest = new ContactSearchRequest();
      if ($after) {
            $afterFilter = new ContactFilter();
            $afterFilter->setOperator('LT');
            $afterFilter->setPropertyName('hs_object_id');
            $afterFilter->setValue($after);

            $contactGroup = new ContactFilterGroup();
            $contactGroup->setFilters([$afterFilter]);
            $contactSearchRequest->setFilterGroups([$contactGroup]);
        }

      if($query){
        $contactSearchRequest->setQuery($query);
      }

      $contactSearchRequest->setLimit($limit);
      $contactSearchRequest->setSorts($sort);
      $contactSearchRequest->setProperties($properties);

      $apiResponse = Cache::remember('contact-'.$after.'-'.$query, 4 , function () use ($contactSearchRequest) {
            return $this->hubspot->crm()->contacts()->searchApi()->doSearch($contactSearchRequest);
      });

      return $apiResponse;



    } catch (ContactApiException $e) {
        dd($e);
    }

  }


  /*** Hubspot Function to create Deals ***/

  public function create_hubspot_deal($orderId){
      $orderId = base64_decode($orderId);
      $dealId = Artisan::call('create:hubspot_deal', [
        'order' => $orderId, '--queue' => 'default'
      ]);

      if($dealId){
        return redirect()->back()->with('success', 'Deal with id #'.$dealId.' generated succesfully.');
      } else {
        return redirect()->back()->withErrors(['msg' => 'Error creating the Deal']);
      }

  }



}
