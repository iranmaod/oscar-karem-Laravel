<x-app-layout>
    <x-slot name="header_content">
        <h1>{{__("Dashboard")}}</h1>
    </x-slot>



    <div class="row">
      <div class="col-lg-12 col-md-12 col-12 col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4>{{__("Filters")}}</h4>
            <div class="card-header-action">

            </div>
          </div>
          <div class="card-body">
            <div class=" hs-ok-filter-wrap">
                <form class="form-row" action="{{ route('dashboard')}}" method="get">
                  <div class="hs-ok-agent-overview-filter form-group col-sm-3 col-md-3">
                    <label class="control-label">{{__("Start Date")}}:</label>
                    <input type="date" class="form-control" value="{{$filter_start_date}}" name="start_date" />
                  </div>
                  <div class="hs-ok-agent-overview-filter form-group col-sm-3 col-md-3">
                    <label class="control-label">{{__("End Date")}}:</label>
                    <input type="date" class="form-control" value="{{$filter_end_date}}" name="end_date" />
                  </div>
                  <div class="hs-ok-agent-overview-button form-group col-sm-3 col-md-3">
                    <label class="control-label"> </label>
                    <button type="submit" class="btn btn-primary form-control">{{__("Search")}}</button>
                  </div>
                  <div class="hs-ok-agent-overview-button form-group col-sm-3 col-md-3">
                    <label class="control-label"> </label>
                    <a href="{{route('dashboard')}}" class="btn btn-primary form-control">{{__("Reset")}}</a>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>



   <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                  <i class="fas fa-euro-sign"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>{{__("Total Liquidity Recieved")}}</h4>
                  </div>
                  <div class="card-body">
                    {{hs_ok_money_format($liquidityRecieved)}}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                  <i class="fas fa-chart-pie"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>{{__("Cancellation Rate %")}}</h4>
                  </div>
                  <div class="card-body">
                    {{round($cancellationRate)}}%
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                  <i class="fas fa-coins"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>{{__("Average Order Value")}}</h4>
                  </div>
                  <div class="card-body">
                    {{hs_ok_money_format($avgOrderValue)}}
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                  <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>{{__("Total Orders")}}</h4>
                  </div>
                  <div class="card-body">
                    {{$totalOrder}}
                  </div>
                </div>
              </div>
            </div>
          </div>



          <div class="row">
            <div class="col-lg-12 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>{{__("Agent Report at Glance")}}</h4>
                  <div class="card-header-action">

                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-striped mb-0">
                      <thead>
                        <tr>
                          <th>{{__("#")}}</th>
                          <th>{{__("Name")}}</th>
                          <th>{{__("Transaction")}}</th>
                          <th>{{__("Liquidity")}}</th>
                          <th>{{__("Cancellation Rate")}}</th>
                          <th>{{__("Avg. Order Value")}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($agents as $key=>$agent)

                        @php
                          $agentTotalOrder = \App\Models\Order::count();
                          $agentTransaction = $agent->order->count();
                          $agentLiquidity = $agent->order->sum('paid_amount');
                          $agentCancelledOrder = \App\Models\Order::whereBetween('created_at', [$start_date, $end_date])      ->where(function($q) use ($agent) {
                              $q->where('agent_id', $agent->hs_vid)
                                ->orWhere('setter_id', $agent->hs_vid);
                          })->where('order_status_id', 5)->count();
                          $agentAvgOrderValue = $agent->order->avg('amount');
                          $agentCancellationRate =  $agentCancelledOrder && $agentTotalOrder ? $agentCancelledOrder/$agentTotalOrder * 100 : 0;
                        @endphp
                        <tr>
                          <td>{{$key + 1}}</td>
                          <td>{{$agent->first_name}} {{$agent->last_name}}</td>
                          <td>{{$agentTransaction}}</td>
                          <td>{{hs_ok_money_format($agentLiquidity)}}</td>
                          <td>{{round($agentCancellationRate)}}%</td>
                          <td>{{hs_ok_money_format($agentAvgOrderValue)}}</td>
                        </tr>
                        @empty
                        <tr>
                          <div class="alert alert-danger">
                            {{__("No Data found")}}
                          </div>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
</x-app-layout>
