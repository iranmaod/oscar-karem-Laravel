<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Commission Overview') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
          <div class="breadcrumb-item"><a href="{{ route('commission') }}">{{__("Commission")}}</a></div>
          <div class="breadcrumb-item"><a href="{{ route('commission') }}">{{__("Commission Overview")}}</a></div>
        </div>
    </x-slot>

    <div>
        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white">
              <div class="flex pb-4 hs-ok-filter-wrap">
                  <form class="form-row" action="{{ route('commission')}}" method="get">
                    <div class="form-group col-sm-3 col-md-3">
                      <label class="control-label">{{__("Filter Date Type")}}</label>
                      <select type="filter_date" class="form-control"  name="filter_date_type">
                        <option value="createdate" {{$filter_date_type == "createdate" ? "selected" : ""}}>{{__("Create Date")}}</option>
                        <option value="closedate"  {{$filter_date_type == "closedate" ? "selected" : ""}}>{{__("Close Date")}}</option>
                        <option value="installment_paid_date" {{$filter_date_type == "installment_paid_date" ? "selected" : ""}}>{{__("Installment Paid Date")}}</option>
                      </select>
                    </div>

                    <div class="hs-ok-agent-overview-filter form-group col-sm-3 col-md-3">
                      <label class="control-label">{{__("Start Date")}}:</label>
                      <input type="date" class="form-control" value="{{$filter_start_date}}" name="start_date" />
                    </div>
                    <div class="hs-ok-agent-overview-filter form-group col-sm-3 col-md-3">
                      <label class="control-label">{{__("End Date")}}:</label>
                      <input type="date" class="form-control" value="{{$filter_end_date}}" name="end_date" />
                    </div>
                    <div class="form-group col-sm-3 col-md-3">
                      <label class="control-label">{{__("Filter by Product")}}</label>
                      <select type="filter_date" class="form-control"  name="filter_product">
                        <option value="">{{__("Select Product")}}</option>
                        @forelse($products as $product)
                        <option value="{{$product}}" {{$filter_product == $product ? "selected" : ""}}>{{__($product)}}</option>
                        @empty
                        @endforelse
                      </select>
                    </div>
                    <div class="hs-ok-agent-overview-filter form-group col-sm-3 col-md-3">
                      <label class="control-label">{{__("Search")}}:</label>
                      <input type="text" class="form-control" value="{{$filter_query}}" placeholder="{{__('Search Deal Name')}}" name="filter_query" />
                    </div>
                    <div class="hs-ok-agent-overview-button form-group col-sm-3 col-md-3">
                      <label class="control-label"> </label>
                      <button type="submit" class="btn btn-primary form-control">{{__("Search")}}</button>
                    </div>
                    <div class="hs-ok-agent-overview-button form-group col-sm-3 col-md-3">
                      <label class="control-label"> </label>
                      <a href="{{route('commission')}}" class="btn btn-primary form-control">{{__("Reset")}}</a>
                    </div>
                  </form>
              </div>

              <div class="row">
                  <div class="table-responsive">
                      <table class="table table-bordered table-striped text-sm text-gray-600">
                          <thead>
                              <tr>
                                <th>{{__("ID")}}  </th>
                                <th>{{__("First Name")}}</th>
                                <th>{{__("Last Name")}}</th>
                                <th>{{__("Email")}}</th>
                                <th>{{__("Hubspot ID")}}</th>
                                <th>{{__("Total Deals")}}</th>
                                <th>{{__("Total Sales")}}</th>
                                <th>{{__("Total Commission")}} </th>
                                <th>{{__("Action")}}</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse($agents as $agent)

                              @if(count($agentDeals[$agent->hs_vid]))
                              <tr class="agent-overview">
                                  <td>{{$agent->id}}</td>
                                  <td>{{$agent->first_name}}</td>
                                  <td>{{$agent->last_name}}</td>
                                  <td>{{$agent->email}}</td>
                                  <td>{{$agent->user_id}}</td>
                                  <td>{{count($agentDeals[$agent->hs_vid])}}</td>
                                  <td class="hs-ok-agent-overview-amount-{{$agent->id}} agent-overview-amount-{{$agent->id}}"></td>
                                  <td class="hs-ok-agent-overview-commission-{{$agent->id}} agent-overview-commission-{{$agent->id}}"></td>
                                  <td class="whitespace-no-wrap row-action--icon">
                                      <a role="button"  data-toggle="collapse" href="#deals-{{$agent->id}}" role="button" aria-expanded="false" aria-controls="#deals-{{$agent->id}}" class="mr-3 btn btn-primary"><i class="fas fa-plus-square"></i> {{__("Show Deals")}}</a>
                                  </td>
                              </tr>
                              <tr class="collapse deal-overview hs-ok-deal-overview" data-agent-id="{{$agent->id}}" id="deals-{{$agent->id}}">
                                <td colspan="9">
                                  <div class="col-12 col-md-12 col-lg-12 hs-ok-deal-overview-wrap">
                                                  <div class="hs-ok-card card">
                                                    <div class="hs-ok-card-header card-header">
                                                      <h4>{{__("Deals of")}} {{$agent->first_name}}</h4>
                                                    </div>
                                                    <div class="card-body">
                                                      <div class="table-responsive">
                                                        <table class="table table-bordered table-md">
                                                          <tbody><tr>
                                                            <th> {{__("#")}} </th>
                                                            <th>{{__("Deal Name")}}</th>
                                                            <th>{{__("Deal Stage")}}</th>
                                                            <th>{{__("Type of Lead/Close")}}</th>
                                                            <th>{{__("Setup-Caller")}}</th>
                                                            <th>{{__("Closer")}}</th>
                                                            <th>{{__("Create Date")}}</th>
                                                            <th>{{__("Close Date")}}</th>
                                                            <th>{{__("Paid / No. of Installments")}}</th>
                                                            <th>{{__("Deal Amount")}}</th>
                                                            <th>{{__("Commission per Installment")}}</th>
                                                            <th>{{__("Commission")}}</th>
                                                            <th>{{__("Action")}}</th>
                                                          </tr>
                                                          @php
                                                          $calculated_amount = 0;
                                                          $calculated_commission = 0;
                                                          $calculated_installment_commission = 0
                                                          @endphp

                                                          @forelse($agentDeals[$agent->hs_vid] as $key=>$deal)

                                                            @php

                                                              $agent_type = agent_type($deal['hubspot_owner_id'], $deal['set_up_caller'], $agent->hs_vid, $deal['dealstage']);
                                                              $agent_slug = $agent_type['slug'] == "setter" ? "setter" : "closer";
                                                              $agent_deal_number = agent_deal_number($deal['hubspot_owner_id'], $deal['set_up_caller'], $deal['setter_deal_number'], $deal['closer_deal_number'], $agent->hs_vid);
                                                              $lead_type = detect_lead_type($deal['comission'] ?? null);
                                                              $lead_type = ($deal['is_individual'] && $lead_type != "customer_leads") ? $lead_type . '_individual' : $lead_type;
                                                              $installment = $deal['number_of_installments']&&  $deal['number_of_installments'] > 0 ? $deal['number_of_installments'] : 1;
                                                            @endphp
                                                          <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td>{{$deal['dealname'] ?? null}}</td>
                                                            <td>{{$deal['dealstage'] == "presentationscheduled" ? "AUFTRAG GEWONNEN - MIT SETTER" : null}} {{$deal['dealstage'] == "6606107" ? "AUFTRAG GEWONNEN - OHNE SETTER" : null}}</td>
                                                            <td>{{'#'.$agent_deal_number}} {{$agent_type['name']}}  {{$deal['comission'] ? "- ".$deal['comission'] : "" }}</td>
                                                            <td>{{$deal['set_up_caller'] ? agent_data($deal['set_up_caller']) : null}}</td>
                                                            <td>{{$deal['hubspot_owner_id'] ? agent_data($deal['hubspot_owner_id']) : null}}</td>
                                                            <td>{{$deal['createdate'] ?  \Carbon\Carbon::parse($deal['createdate'])->format('d/m/Y') : null}}</td>
                                                            <td>{{$deal['closedate'] ?  \Carbon\Carbon::parse($deal['closedate'])->format('d/m/Y') : null}}</td>

                                                            <td>{{$deal['paid_installments'] ?? 1 }}/{{$installment}}</td>
                                                            <td>
                                                              @php
                                                              $deal_amount = $deal['amount'];
                                                              $calculated_amount += $deal_amount;
                                                              @endphp
                                                              {{hs_ok_money_format($deal_amount) ?? null}}
                                                            </td>
                                                            <td>
                                                              @php
                                                              $deal_commission = calculate_commission($deal['amount'], $agent_deal_number, $agent_slug, $agent->commission_payment_type, $lead_type);
                                                              $calculated_commission += $deal_commission;
                                                              $installment_commission  = $installment && is_numeric($installment) ? $deal_commission/$installment : $deal_commission;
                                                              $calculated_installment_commission += $installment_commission;
                                                              @endphp
                                                              <span class="text-primary">{{ hs_ok_money_format($installment_commission) ?? null}}</span>

                                                            </td>
                                                            <td>
                                                              <span class="text-primary">{{ hs_ok_money_format($deal_commission) ?? null}}</span>
                                                            </td>
                                                            <td><a href="{{env('HUBSPOT_URL')}}/contacts/{{env('HUBSPOT_ID')}}/deal/{{$deal['hs_object_id'] ?? null}}" class="btn btn-primary" target="_blank">{{__("Open")}}</a></td>
                                                          </tr>
                                                          @empty
                                                          @endforelse
                                                          <tr class="hs-ok-deals-footer">
                                                            <td colspan="7" class="hs-ok-total">{{__('Total')}}</td>
                                                            <td class="deals-calculated-amount" data-calculated-amount="{{ hs_ok_money_format($calculated_amount) }}">{{ hs_ok_money_format($calculated_amount) }}</td>
                                                            <td class="deals-calculated-commission" data-calculated-commission="{{hs_ok_money_format($calculated_installment_commission)}}"><span class="text-primary">{{hs_ok_money_format($calculated_installment_commission)}}</span></td>
                                                            <td></td>
                                                          </tr>
                                                        </tbody></table>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>

                                </td>
                              </tr>
                              @endif
                              @empty
                              @endforelse
                          </tbody>
                      </table>
                  </div>
              </div>
              <x-slot name="script">
              <script>
                $(document).ready(function(){
                    $(".deal-overview").each(function(index, item){
                      var agentId = $(this).data('agent-id');
                      var calculatedAmount = $(this).find('.deals-calculated-amount').data('calculated-amount');
                      var calculatedCommission = $(this).find('.deals-calculated-commission').data('calculated-commission');
                      $(".agent-overview-amount-"+agentId).text(calculatedAmount);
                      $(".agent-overview-commission-"+agentId).text(calculatedCommission);
                    });
                });
              </script>
            </x-slot>

          </div>
        </div>
    </div>
</x-app-layout>
