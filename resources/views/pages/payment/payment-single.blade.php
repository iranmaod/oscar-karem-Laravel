<x-app-layout>
    <x-slot name="header_content">
        <h1>{{  @$order->id ? 'View Payment : #HSE-'.$order->id : 'View Payment'}}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment') }}">Payments</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment') }}">{{  @$order->id ? 'View Payment : #HSE-'.$order->id : 'View Payment'}}</a></div>
        </div>
    </x-slot>

    <div>
      <div class="hs-ok-content section-body">
                        <div>
        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white">
              <div class="flex pb-4 -ml-3">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-sm text-gray-600">
                        <tbody>
                          <tr>
                                <td>{{__('Name')}}</td>
                                <td>{{$order->firstname}} {{$order->lastname}}</td>
                          </tr>
                          <tr>
                                <td>{{__('Email')}}</td>
                                <td>{{$order->email}}</td>
                          </tr>
                          <tr>
                                <td>{{__('Plan')}}</td>
                                <td>{{ $order->payment_type == 'installment' ? "Initial Payment & ".@$order->installment->name : "One Time Payment"  }}</td>
                          </tr>
                          <tr>
                                <td>{{__('Amount')}}</td>
                                <td>{{hs_ok_money_format($order->amount)}}</td>
                          </tr>
                          <tr>
                                <td>{{__('Vat Percentage')}}</td>
                                <td>{{is_numeric($order->vat_percentage->percentage) ? $order->vat_percentage->percentage : 0}}%</td>
                          </tr>
                          <tr>
                                <td>{{__('Total Amount (Including Vat)')}}</td>
                                <td>{{hs_ok_money_format($order->total_amount)}}</td>
                          </tr>
                          <tr>
                                <td>{{__('Paid Amount')}}</td>
                                <td>{{hs_ok_money_format($order->paid_amount)}}</td>
                          </tr>
                          @if($order->payment_type == 'installment')
                          <tr>
                                <td>{{__('Upfront Amount')}}</td>
                                <td>{{$order->downpayment_amount}}</td>
                          </tr>
                          <tr>
                                <td>{{__('Installment Plan')}}</td>
                                <td>{{$order->installment->name}}</td>
                          </tr>
                          <tr>
                                <td>{{__('Installment Frequency')}}</td>
                                <td>{{ucfirst($order->installment_frequency)}}</td>
                          </tr>
                          @endif
                          <tr>
                                <td>{{__('Status')}}</td>
                                <td>{{ucfirst($order->status->name)}}</td>
                          </tr>
                        </tbody>
                    </table>
                </div>
              </div>

              <div class="row">
                  <div class="table-responsive">
                      <table class="table table-bordered table-striped text-sm text-gray-600">
                          <thead>
                              <tr>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Transaction Id')}}</th>
                                <th>{{__('Sevdesk Id')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Remark')}}</th>
                                <th>{{__('Status')}}</th>
                              </tr>
                          </thead>
                          <tbody>
                              @forelse($order->transaction as $transaction)
                             <tr>
                                  <td>{{$transaction->date}}</td>
                                  <td>{{$transaction->transaction_id}}</td>
                                  <td>{{$transaction->sevdesk_transaction_id}}</td>
                                  <td>{{hs_ok_money_format($transaction->amount)}}</td>
                                  <td>{{$transaction->remark}}</td>
                                  <td>{{ucfirst($transaction->status)}}</td>
                            </tr>
                            @empty
                            <tr>
                                 <td colspan="6">
                                   <div class="alert alert-danger mt-4">
                                      No Transactions Found.
                                    </div>
                                 </td>
                           </tr>
                            @endforelse

                          </tbody>
                      </table>
                  </div>
              </div>

          </div>
        </div>
    </div>
                      </div>
    </div>
</x-app-layout>
