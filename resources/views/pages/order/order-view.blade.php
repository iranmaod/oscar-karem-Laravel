<x-frontend-layout>
    <x-slot name="header_content">
        <h1>{{ __('Edit Order') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
        <div class="breadcrumb-item"><a href="{{ route('order') }}">{{__("Orders")}}</a></div>
        <div class="breadcrumb-item"><a href="#">{{__("Edit Orders")}}</a></div>
        </div>
    </x-slot>

    <div>
        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white">
              <div class="flex pb-4 -ml-3">
                  <h6>{{__('Order Information: Order No. #')}}{{$order->id}}</h6>
              </div>
              <div class="row">
                <form class="form-row" method="post" action="{{route('order.checkout.process')}}">
                  @csrf
                  <div class="form-group col-md-6 required">
                    <input type="hidden" name="order_id" value="{{base64_encode($order->id)}}">
                    <label class="control-label">{{__('First name')}}</label>
                    <input type="text" class="form-control" value="{{ $order->firstname }}" name="firstname" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Last name')}}</label>
                    <input type="text" class="form-control" value="{{ $order->lastname }}" name="lastname" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('E-mail')}}</label>
                    <input type="text" class="form-control" value="{{ $order->email }}" name="email" disabled required/>
                  </div>
                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Phone Number')}}</label>
                    <input type="text" class="form-control" value="{{ $order->phone }}" name="phone" disabled required/>
                  </div>
                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Product Selection')}}</label>
                    <select id="product" class="form-control" name="elopage_product_id" disabled required/>
                       @forelse($products as $product )
                       <option value="{{ base64_encode(($product['id'] ?? null).'#^#'.($product['name'] ?? null)) }}" {{($order->product_id == $product['id']) ? 'selected' : '' }} >{{ $product['name'] ?? null }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>
                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Payment')}}</label>
                    <select id="product" class="form-control" name="elopage_product_id" disabled required/>
                       @forelse($payment_methods as $payment_method )
                       <option value="{{ $payment_method->id }}" {{($order->payment_method_id == $payment_method->id) ? 'selected' : '' }} >{{ $payment_method->name }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('One-time payment')}} / {{__('Installments')}}</label>
                    <select id="installment" class="form-control" name="installment_id" disabled required/>
                       <option value="" selected="">{{__('Choose Installment')}}</option>
                       @forelse($installments as $installment )
                       <option value="{{ $installment->id }}" {{($order->installment_id == $installment->id) ? 'selected' : '' }}>{{ $installment->name }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Quantity')}}</label>
                    <input type="text" class="form-control" name="quantity" value="{{$order->qty}}" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Address Line')}}</label>
                    <input type="text" class="form-control" name="address" value="{{$order->address}}" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('City')}}</label>
                    <input type="text" class="form-control" name="city" value="{{$order->city}}" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Country')}} / {{__('Region')}}</label>
                    <input type="text" class="form-control" name="country" value="{{$order->country->name}}" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('PLZ')}}</label>
                    <input type="text" class="form-control" name="plz" value="{{$order->plz}}" disabled required/>
                  </div>

                  <div class="form-group col-md-12 required">
                    <label class="control-label">{{__('Company Name')}}</label>
                    <input type="text" class="form-control" name="company_name" value="{{$order->company_name}}" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('VAT ID no.')}}</label>
                    <input type="text" class="form-control" name="vat" value="{{$order->vat}}" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('VAT%')}}</label>
                    <select id="vat_percentage" class="form-control" name="vat_percentage_id" disabled required/>
                       <option value="" selected="">{{__('Choose Vat%')}}</option>
                       @forelse($vat_percentages as $vat_percentage )
                       <option value="{{ $vat_percentage->id }}" {{($order->vat_percentage_id == $vat_percentage->id) ? 'selected' : '' }} >{{ $vat_percentage->name }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>
                </form>
              </div>

              <div class="flex pb-4 -ml-3">
                  <h6>{{__('Terms accepted for Order No. #')}}{{$order->id}}</h6>
              </div>
              <div class="row">
                <div class="form-group col-md-12 required">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" checked="checked" id="hs-extension-oblige-1" disabled readonly>
                      <label class="form-check-label" for="hs-extension-oblige-1">
                        Durch Setzen des Häkchens erkläre ich mich mit der Geltung der <a href="#" target="_blank">AGB</a> einverstanden. Von meinem <a href="#" target="_blank">Widerrufsrecht</a> habe ich Kenntnis genommen.
                      </label>
                    </div>
                </div>
                <div class="form-group col-md-12 required">
                    <div class="form-check">
                      <input class="form-check-input" checked="checked" type="checkbox" id="hs-extension-oblige-1" disabled readonly>
                      <label class="form-check-label" for="hs-extension-oblige-2">
                      <u>Verzicht auf das Widerrufsrecht über die Lieferung von digitalen Inhalten:</u> Durch Setzen des Häkchens stimme ich ausdrücklich zu, dass der Unternehmer mit der Ausführung des Vertrags vor Ablauf der Widerrufsfrist beginnt und bestätige meine Kenntnis davon, dass ich durch meine Zustimmung mein Widerrufsrecht verliere.
                      </label>
                    </div>
                </div>
                <div class="form-group col-md-12 required">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox"  checked="checked" id="hs-extension-oblige-2"  disabled readonly>
                      <label class="form-check-label" for="hs-extension-oblige-3">
                        <u>Erlöschen des Widerrufsrechts bei Fernabsatzverträgen über Dienstleistungen</u>: Durch Setzen des Häkchens stimme ich ausdrücklich zu, dass der Unternehmer mit der Ausführung des Vertrags vor Ablauf der Widerrufsfrist beginnt und bestätige meine Kenntnis davon, dass mein Widerrufsrecht mit vollständiger Vertragserfüllung durch den Unternehmer erlischt.
                      </label>
                    </div>
                </div>
                @if($order->payment_method->slug == 'abilita-dd' || $order->payment_method->slug == 'abilita-dd_b2b')
                <div class="form-group col-md-12 required">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" checked="checked" id="hs-sepa-oblige" readonly disabled>
                      <label class="form-check-label" for="hs-sepa-oblige">
                        Ich stimme zu, dass dieser Risikocheck durchgeführt wird
                      </label>
                    </div>
                </div>
                @endif
              </div>

              <div class="flex pb-4 -ml-3">
                  <h6>{{__('Additional Information: Order No. #')}}{{$order->id}}</h6>
              </div>

              <div class="row">
                <div class="table-responsive table-invoice">
                    <table class="table table-striped">
                      <tbody>
                      <tr>
                        <td>{{__('IP Address')}}</td>
                        <td class="font-weight-600">{{$order->ip_address}}</td>
                      </tr>
                      <tr>
                        <td>{{__('Date of Purchase')}}</td>
                        <td class="font-weight-600">{{$order->created_at}}</td>
                      </tr>
                       @if($order->sevdesk_invoice_id)
                      <tr>
                        <td>{{__('SevDesk Invoice')}}</td>
                        <td class="font-weight-600">
                            <a href="https://my.sevdesk.de/#/fi/detail/type/RE/id/{{$order->sevdesk_invoice_id}}" class="btn btn-info" target="blank">SevDesk Invoice</a></td>
                      </tr>
                      @endif
                      @if($order->sevdesk_invoice_id)
                      <tr>
                        <td>{{__('Elopage Order')}}</td>
                        <td class="font-weight-600"><a href="https://elopage.com/cabinet/courses/sessions?blocked=false&page=1&per=10&query={{urlencode($order->email)}}&sortDir=desc&sortKey=id&product_id={{$order->product_id}}" class="btn btn-info" target="blank">Elopage Order</a></td>
                      </tr>
                      @endif
                    </tbody></table>
                  </div>
              </div>
              <div class="flex pb-4 -ml-3">
                  <h6>{{__('Payment Information: Order No. #')}}{{$order->id}}</h6>
              </div>
              <div class="row">
                <div class="table-responsive table-invoice">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>{{__('Transaction No.')}}</th>
                          <th>{{__('Status')}}</th>
                          <th>{{__('Payment Method')}}</th>
                          <th>{{__('Paid on')}}</th>
                          <th>{{__('Amount')}}</th>
                          <th>{{__('Transaction Id')}}</th>
                          <th>{{__('IP Address')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                    @forelse($order->transaction as $key=>$record)
                      <tr>
                        <td>{{$key+1}}</td>
                        <th>{{$record->status}}</th>
                        <td>{{@$record->payment_method->name}}</td>
                        <td>{{$record->created_at->format('F d, Y H:i:s')}}</td>
                        <td>{{hs_ok_money_format($record->amount)}}</td>
                        <td>{{$record->transaction_id}}</td>
                        <td>{{$record->ip_address}}</td>
                      </tr>
                   @empty
                   @endforelse
                    </tbody></table>
                  </div>
              </div>
              <br/>
              <div class="flex pb-4 -ml-3">
                  <h6>{{__('Installment Information: Order No. #')}}{{$order->id}}</h6>
              </div>
              <div class="row">
                <div class="table-responsive table-invoice">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>{{__('Installment')}}</th>
                          <th>{{__('Amount')}}</th>
                          <th>{{__('Due Date')}}</th>
                          <th>{{__('Paid Date')}}</th>
                          <th>{{__('Payment Method')}}</th>
                          <th>{{__('Transaction Id')}}</th>
                          <!--<th>Action</th>-->
                        </tr>
                      </thead>
                      <tbody>
                        @php
                        $installmentSchedule = \App\Models\InstallmentSchedule::where('order_id', $order->id)->get();
                        
                        @endphp
                        @forelse($installmentSchedule as $key=>$record)
                          <tr>
                            <td>{{$record->installment}}</td>
                            <td>{{hs_ok_money_format($record->amount)}}</td>
                            <td>{{hs_ok_time_format($record->due_date)}}</td>
                            <td>{{hs_ok_time_format($record->paid_date)}}</td>
                            <td>
                                <?php
                                    $record->order = \App\Models\Order::where('id', $record->order_id)->get()->first();
                                
                                ?>
                                
                                {{$record->order->payment_method->name}}
                                
                                <!--{{@$record->payment->payment_method->name}}-->
                            </td>
                            <td>{{@$record->transaction_id}}</td>
                            
                         <!--start   -->
                        <!--<td class="">-->
                   
                        <!--<a href="{{ route('order.checkout.email', [$record->id, $order->id] )}}" class="btn btn-primary">Email</a>-->
                        
                        <!-- </td>-->
                         <!--end-->
                          </tr>
                       @empty
                       @endforelse
                    </tbody>
                  </table>
                  </div>
              </div>


          </div>
        </div>
    </div>
</x-frontend-layout>
