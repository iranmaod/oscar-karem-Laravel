<x-frontend-layout>
    <x-slot name="header_content">
        <div class="hs-ok-breadcrumb section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
          <div class="breadcrumb-item"><a href="{{ route('order') }}">Orders</a></div>
        </div>
    </x-slot>

        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white">
              <div class="flex pb-4 -ml-3">
                <p>
                <strong>BlackWood GmbH</strong><br/>
                1030 Wien<br/>
                ATU 74884039<br/><br/>

                <em>HR-Nr.: FN 520369 z I U-ID: ATU74884039 I Steuer-Nr.: 22/348/493 I GISA-Zahl: 32254808 I AT873200200003830395 I BIC: RLNWATW1002 I Geschäftsführung: Oscar Karem</em>
                </p>
              </div>

              <div class="row">
                <h3>{{__('Order Overview')}}</h3>
                <form class="form-row" method="post" action="{{route( request('checkout') == 'resume' ? 'order.payment.resume':'order.checkout.process')}}">
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

                  @if($order->account)
                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('IBAN No.')}}</label>
                    <input type="text" class="form-control" value="{{ partial_decrypt_hs_string($order->account, 5) }}" name="account" disabled required/>
                  </div>
                  @endif

                  @if($order->b_account)
                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('BICS No.')}}</label>
                    <input type="text" class="form-control" value="{{ partial_decrypt_hs_string($order->b_account, 5) }}" name="b_account" disabled required/>
                  </div>
                  @endif

                  @if($order->gender)
                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Gender')}}</label>
                    <input type="text" class="form-control" value="{{ $order->gender == 'm' ? 'Male' : 'Female' }}" name="gender" disabled required/>
                  </div>
                  @endif

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Product Selection')}}</label>
                    <select id="product" class="form-control" name="elopage_product_id" disabled required/>
                       @forelse($products as $product )
                       <option value="{{ base64_encode(($product['id'] ?? null).'#^#'.($product['name'] ?? null)) }}" {{($order->product_id == $product['id']) ? 'selected' : '' }} >{{ $product['name'] ?? null }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>
                  <div class="form-group col-md-12 required">
                    <label class="control-label">{{__('Product Description')}}</label>
                    <blockquote>
                      {{$order->product->description}}
                    </blockquote>
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
                    <label class="control-label">{{__('Payment Type')}}</label>
                    <input type="text" class="form-control" value="{{ $order->payment_type == 'installment' ? 'Installments' : 'One-Time' }}" name="installment_frequency" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Amount')}}</label>
                    <input type="text" class="form-control" value="{{ hs_ok_money_format($order->amount)}}" name="amount" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Total Amount')}}</label>
                    <input type="text" class="form-control" value="{{ hs_ok_money_format($order->total_amount)}}" name="total_amount" disabled required/>
                  </div>

                  @if(request('checkout') == 'resume')
                  <div class="form-group col-md-6 required">
                    <label class="control-label"><strong>{{__('Pending Amount')}}</strong></label>
                    <input type="text" class="form-control" value="{{ hs_ok_money_format($order->total_amount - $order->paid_amount)}}" name="pending_amount" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label"><strong>{{__('Pending Installments')}}</strong></label>
                    <input type="text" class="form-control" value="{{ ($order->installment->billing_threshold +1) - $order->transaction->where('status', 'completed')->count() }}" name="pending_installment" disabled required/>
                  </div>
                  @endif

                  @if($order->payment_type == 'installment')
                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Installments')}}</label>
                    <select id="installment" class="form-control" name="installment_id" disabled required/>
                       <option value="" selected="">{{__('Choose Installment')}}</option>
                       @forelse($installments as $installment )
                       <option value="{{ $installment->id }}" {{($order->installment_id == $installment->id) ? 'selected' : '' }}>{{ $installment->name }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Installment Frequency')}}</label>
                    <input type="text" class="form-control" value="{{ $order->installment_frequency == 'monthly' ? 'Monthly' : 'Weekly' }}" name="installment_frequency" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Intial Amount')}}</label>
                    <input type="text" class="form-control" value="{{ hs_ok_money_format($order->downpayment_amount)}}" name="initial_amount" disabled required/>
                  </div>

                  <div class="form-group col-md-6 required">
                    <label class="control-label">{{__('Installment Start Date')}}</label>
                    <input type="text" class="form-control" value="{{ $order->installment_start_date}}" name="installment_start_date" disabled required/>
                  </div>
                  @endif

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
                    <label class="control-label">{{__('VAT %')}}</label>
                    <select id="vat_percentage" class="form-control" name="vat_percentage_id" disabled required/>
                       <option value="" selected="">{{__('Choose Vat%')}}</option>
                       @forelse($vat_percentages as $vat_percentage )
                       <option value="{{ $vat_percentage->id }}" {{($order->vat_percentage_id == $vat_percentage->id) ? 'selected' : '' }} >{{ $vat_percentage->name }}</option>
                       @empty
                       @endforelse
                    </select>
                  </div>
                  <!-- <div class="form-group col-md-12 required">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="hs-extension-oblige" required>
                        <label class="form-check-label" for="hs-extension-oblige">
                          Ich akzeptiere die <a href="https://www.blackwood.at/impressum-2/" target="blank">AGB</a> der BlackWood GmbH terms and conditions.
                        </label>
                      </div>
                  </div> -->
                </form>
          </div>
          <div class="flex pb-4 -ml-3">
              <h6>{{__('Amount Overview')}}</h6>
          </div>
          <div class="row">
            <table class="table table-bordered table-striped text-sm text-gray-600">
                <tbody>
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
                  @if($order->payment_type == 'installment')
                  <tr>
                        <td>{{__('Upfront Amount')}}</td>
                        <td>{{hs_ok_money_format($order->downpayment_amount)}}</td>
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
                </tbody>
            </table>
          </div>
          @if($order->payment_type == 'installment')
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
                      <th>{{__('Due on')}}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $installment_schedules = generate_installment_schedule($order, false, false);
                    @endphp
                    @forelse($installment_schedules as $record)
                    <tr>
                      <td>{{$record['installment']}}</td>
                      <td>{{hs_ok_money_format($record['amount'])}}</td>
                      <td>{{hs_ok_time_format($record['due_date'], 'Y-m-d', 'F d, Y')}}</td>
                    </tr>
                    @empty
                    @endforelse
                  </tbody>
              </table>
              </div>
          </div>
          @endif
          <div class="row">
            <form class="form-row" method="post" action="{{route( request('checkout') == 'resume' ? 'order.payment.resume':'order.checkout.process')}}">
              @csrf
              <input type="hidden" name="order_id" value="{{base64_encode($order->id)}}">
              <div class="form-group col-md-12 required">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="hs-extension-oblige-1" required>
                    <label class="form-check-label" for="hs-extension-oblige-1">
                      Durch Setzen des Häkchens erkläre ich mich mit der Geltung der <a href="https://www.blackwood.at/agb/ " target="_blank">AGB</a> einverstanden. Von meinem <a href="https://www.blackwood.at/widerrufsbelehrung/ " target="_blank">Widerrufsrecht</a> habe ich Kenntnis genommen.
                    </label>
                  </div>
              </div>
              <div class="form-group col-md-12 required">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="hs-extension-oblige-2" required>
                    <label class="form-check-label" for="hs-extension-oblige-2">
                    <u>Verzicht auf das Widerrufsrecht über die Lieferung von digitalen Inhalten:</u> Durch Setzen des Häkchens stimme ich ausdrücklich zu, dass der Unternehmer mit der Ausführung des Vertrags vor Ablauf der Widerrufsfrist beginnt und bestätige meine Kenntnis davon, dass ich durch meine Zustimmung mein Widerrufsrecht verliere.
                    </label>
                  </div>
              </div>

              <div class="form-group col-md-12 required">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="hs-extension-oblige-3" required>
                    <label class="form-check-label" for="hs-extension-oblige-3">
                      <u>Erlöschen des Widerrufsrechts bei Fernabsatzverträgen über Dienstleistungen</u>: Durch Setzen des Häkchens stimme ich ausdrücklich zu, dass der Unternehmer mit der Ausführung des Vertrags vor Ablauf der Widerrufsfrist beginnt und bestätige meine Kenntnis davon, dass mein Widerrufsrecht mit vollständiger Vertragserfüllung durch den Unternehmer erlischt.
                    </label>
                  </div>
              </div>

              @if($order->payment_method->slug == 'abilita-dd' || $order->payment_method->slug == 'abilita-dd_b2b')
              <div class="form-group col-md-12 required">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="hs-sepa-oblige" required>
                    <label class="form-check-label" for="hs-sepa-oblige">
                      Ich stimme zu, dass dieser Risikocheck durchgeführt wird
                    </label>
                  </div>
              </div>
              @endif
              &nbsp;
              <br/>
              <div class="form-group col-md-12">
                <button class="btn btn-primary" type="submit">Jetzt zahlungspflichtig bestellen</button>
              </div>
            </form>
          </div>
        </div>
    </div>
</x-frontend-layout>
