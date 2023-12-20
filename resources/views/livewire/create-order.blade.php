<div id="form-create">
    <x-jet-form-section :submit="$action" class="mb-4">
        <x-slot name="title">
            {{ __('Order') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Complete the following data and submit to create a new Order') }}

        </x-slot>



        <x-slot name="form">
            @if ($errors->any())
            <div class="form-group col-span-12 md:col-span-12">
              <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
                </ul>
              </div>
            </div>
            @endif
            @if(isset($order->id))
            <div class="form-group col-span-12 md:col-span-12">
              <div class="section-title mt-0">{{ __('Related Links') }}</div>
              <div class="buttons">
                    @if($order->status->slug == 'hold')
                    <a href="{{route('order.status.set', array('orderId' => base64_encode($order->id), 'statusId' => base64_encode(3) ))}}" class="btn btn-warning">Resume Order</a>
                    @endif
                    @if($order->status->slug != 'paid' && $order->status->slug != 'cancelled' )
                    <a href="{{route('order.status.set', array('orderId' => base64_encode($order->id), 'statusId' => base64_encode(5) ))}}" class="btn btn-danger">Cancel Order</a>
                    @endif
                    @if($order->sevdesk_user_id)
                      <a href="https://my.sevdesk.de/#/crm/detail/id/{{$order->sevdesk_user_id}}" class="btn btn-info" target="blank">SevDesk Contact</a>
                    @else
                      <a href="{{route('generate.contact', array('orderId' => base64_encode($order->id)))}}" class="btn btn-primary">Generate SevDesk Contact</a>
                    @endif
                    @if($order->sevdesk_user_id)
                      @if($order->sevdesk_invoice_id)
                        <a href="https://my.sevdesk.de/#/fi/detail/type/RE/id/{{$order->sevdesk_invoice_id}}" class="btn btn-info" target="blank">SevDesk Invoice</a>
                        <a href="{{route('send.invoice', array('orderId' => base64_encode($order->id)))}}" class="btn btn-primary">Send SevDesk Invoice</a>

                      @else
                        <a href="{{route('generate.invoice', array('orderId' => base64_encode($order->id)))}}" class="btn btn-primary">Generate SevDesk Invoice</a>
                      @endif
                    @endif
                    @if($order->hs_deal_id)
                      <a href="https://app.hubspot.com/contacts/{{env('HUBSPOT_ID')}}/deal/{{$order->hs_deal_id}}" class="btn btn-success" target="blank">Hubspot Deal</a>
                    @else
                      <a href="{{route('generate.deal', array('orderId' => base64_encode($order->id)))}}" class="btn btn-primary">Generate Hubspot Deal</a>
                    @endif

                    @if($order->elopage_order_id)
                        <a href="https://elopage.com/cabinet/courses/sessions?blocked=false&page=1&per=10&query={{urlencode($order->email)}}&sortDir=desc&sortKey=id&product_id={{$order->product_id}}" class="btn btn-success" target="blank">Manage Elopage Access</a>
                    @else
                       <a href="{{route('generate.elopage.order', array('orderId' => base64_encode($order->id)))}}" class="btn btn-primary">Generate Elopage Order</a>
                    @endif
              </div>
            </div>

            @if($order->id && ($order->status->slug == 'generated' || $order->status->slug == 'pending'))
            @php

              $paymentLinkData = array('orderId' => base64_encode($order->id), 'key' => base64_encode($order->auth_code));

              if($order->status->slug == 'pending'){
                $paymentLinkData['checkout'] = 'resume';
              }
            @endphp
            <div class="form-group col-span-12 md:col-span-12">
              <div class="section-title mt-0">Order Url</div>
              <div class="input-group mb-3">
                <input type="text" id="hs-copy-input" value="{{ route('order.checkout', $paymentLinkData)}}" class="form-control" placeholder="" aria-label="" />
                <div class="input-group-append">
                  <button class="btn btn-primary" id="hs-copy-button" type="button" onClick="copyToClipboard('hs-copy-input')">Copy Url</button>
                </div>
              </div>
            </div>
            @endif

            @endif


            <div class="form-group col-span-6 md:col-span-6">
                <x-jet-label for="firstname" value="{{ __('First Name') }}" />
                <x-jet-input id="firstname" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.firstname" />
                <x-jet-input-error for="order.firstname" class="mt-2" />
            </div>

            <div class="form-group col-span-6 md:col-span-6">
                <x-jet-label for="lastname" value="{{ __('Last Name') }}" />
                <x-jet-input id="lastname" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.lastname" />
                <x-jet-input-error for="order.lastname" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" type="email" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.email" />
                <x-jet-input-error for="order.email" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="phone" value="{{ __('Phone') }}" />
                <x-jet-input id="phone" type="phone" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.phone" />
                <x-jet-input-error for="order.phone" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="account" value="{{ __('IBAN No.') }}" />
                <x-jet-input id="account" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.account" />
                <x-jet-input-error for="order.account" class="mt-2" />
                <div class="text-xs text-gray-500">nur für SEPA Zahlungen wichtig</div>
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="b_account" value="{{ __('BICS No.') }}" />
                <x-jet-input id="b_account" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.b_account" />
                <x-jet-input-error for="order.b_account" class="mt-2" />
                <div class="text-xs text-gray-500">nur für SEPA Zahlungen wichtig</div>
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="dob" value="{{ __('Date of Birth') }}" />
                <x-jet-input id="dob" type="date" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.dob" />
                <x-jet-input-error for="order.dob" class="mt-2" />
                <div class="text-xs text-gray-500">nur für SEPA Zahlungen wichtig</div>
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="gender" value="{{ __('Gender') }}" />
                <x-btui-select id="gender" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.gender" :options="$genders" wire:model.defer="order.gender" placeholder="{{__('Select Gender')}}" />
                <x-jet-input-error for="order.gender" class="mt-2" />
                <div class="text-xs text-gray-500">nur für SEPA Zahlungen wichtig</div>
            </div>

            <div class="form-group col-span-6 sm:col-span-12">
                <x-jet-label for="product_id" value="{{ __('Product') }}" />
                <x-btui-select id="product_id" class="mt-1 block w-full form-control shadow-none" wire:click="changeProduct($event.target.value)" :disabled="$isDisabled" name="order.product_id" :options="$products" wire:model.defer="order.product_id" placeholder="{{__('Select Product')}}" />
                <x-jet-input-error for="order.product_id" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="address" value="{{ __('Address') }}" />
                <x-jet-input id="phone" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.address" />
                <x-jet-input-error for="order.address" class="mt-2" />
            </div>



            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="city" value="{{ __('City') }}" />
                <x-jet-input id="city" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.city" />
                <x-jet-input-error for="order.city" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="country_code" value="{{ __('Country') }}" />
                <x-btui-select id="country_code" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.country_code" :options="$countries" wire:model.defer="order.country_code" placeholder="{{__('Select Country')}}" />
                <x-jet-input-error for="order.country_code" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="plz" value="{{ __('Plz') }}" />
                <x-jet-input id="plz" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.plz" />
                <x-jet-input-error for="order.plz" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="company_name" value="{{ __('Company Name') }}" />
                <x-jet-input id="company_name" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.company_name" />
                <x-jet-input-error for="order.company_name" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="vat" value="{{ __('VAT ID') }}" />
                <x-jet-input id="vat" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.vat" />
                <x-jet-input-error for="order.vat" class="mt-2" />
            </div>


            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="vat_percentage_id" value="{{ __('Vat Percentage') }}" />
                <x-btui-select id="vat_percentage_id" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.vat_percentage_id" :options="$vat_percentages" wire:model.defer="order.vat_percentage_id" placeholder="Select VAT Percentage" />
                <x-jet-input-error for="order.vat_percentage_id" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="order_status_id" value="{{ __('Order Status') }}" />
                <x-btui-select id="order_status_id" class="mt-1 block w-full form-control shadow-none" name="order.order_status_id" disabled :options="$order_statuses" wire:model.defer="order.order_status_id" placeholder="{{__('Select Order Status')}}" />
                <x-jet-input-error for="order.order_status_id" class="mt-2" />
            </div>

            <div class="form-group d-none col-span-6 sm:col-span-6">
                <x-jet-label for="start_date" value="{{ __('Start Date') }}" />
                <x-jet-input id="start_date" type="date" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.start_date" />
                <x-jet-input-error for="order.start_date" class="mt-2" />
            </div>

            <div class="form-group d-none col-span-6 sm:col-span-6">
                <x-jet-label for="end_date" value="{{ __('End Date') }}" />
                <x-jet-input id="end_date" type="date" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.end_date" />
                <x-jet-input-error for="order.end_date" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="payment_method_id" value="{{ __('Payment Method') }}" />
                <x-btui-select id="payment_method_id" class="mt-1 block w-full form-control shadow-none" wire:click="changePaymentMethod($event.target.value)" :disabled="$isDisabled" name="order.payment_method_id" :options="$payment_methods" wire:model.defer="order.payment_method_id" placeholder="{{__('Select Payment Method')}}" />
                <x-jet-input-error for="order.payment_method_id" class="mt-2" />
            </div>


            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="payment_type" value="{{ __('Payment Type') }}" />
                <x-btui-select id="payment_type" class="mt-1 block w-full form-control shadow-none" wire:click="changePaymentType($event.target.value)" name="order.payment_type" :options="$payment_types" wire:model.defer="order.payment_type" placeholder="{{__('Select Payment Type')}}" />
                <x-jet-input-error for="order.payment_type" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">

                <x-jet-label for="amount" value="{{ __('Amount') }}" />
                <x-jet-input id="amount" min="0" type="number" class="mt-1 block w-full form-control shadow-none"  wire:change="changeAmount($event.target.value)" :readonly="$order['is_manual_amount'] != 1" wire:model.defer="order.amount" />
                <label>
                  <input id="is_manual_amount" wire:click="changeManualAmount()"  type="checkbox" @if($order['is_manual_amount'] == 1) checked @endif /> {{__('Set Amount Manually')}}
                </label>
                <x-jet-input-error for="order.amount" class="mt-2" />
            </div>

            @if($order['payment_type'] == 'installment')
            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="installment_id" value="{{ __('Installments') }}" />
                <x-btui-select id="installment_id" class="mt-1 block w-full form-control shadow-none" wire:click="changeInstallment($event.target.value)" :disabled="$isDisabled" name="order.installment_id" :options="$installments" wire:model.defer="order.installment_id" placeholder="Select Installments" />
                <x-jet-input-error for="order.installment_id" class="mt-2" />
            </div>
            @endif

            @if($order['payment_type'] == 'installment')
            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="installment_frequency" value="{{ __('Installment Frequency') }}" />
                <x-btui-select id="installment_frequency" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.payment_type" :options="$installment_frequencies" wire:model.defer="order.installment_frequency" placeholder="{{__('Select Installment Frequency')}}" />
                <x-jet-input-error for="order.installment_frequency" class="mt-2" />
            </div>
            @endif

            @if($order['payment_type'] == 'installment' && $order['payment_method'] != '4' )
            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="downpayment_amount" min="0" value="{{ __('Down-payment Amount') }}" />
                <x-jet-input id="downpayment_amount" type="number" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.downpayment_amount" />
                <x-jet-input-error for="order.downpayment_amount" class="mt-2" />
            </div>
            @endif

            @if($order['payment_type'] == 'installment' && $order['payment_method'] != '4')
            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="installment_start_date" value="{{ __('Installment Start Date') }}" />
                <x-jet-input id="installment_start_date" type="date" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.installment_start_date" />
                <x-jet-input-error for="order.installment_start_date" class="mt-2" />
            </div>
            @endif

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="setter_id" value="{{ __('Set-up Caller') }}" />
                <x-btui-select id="setter_id" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.setter_id" :options="$agents" wire:model.defer="order.setter_id" placeholder="{{__('Select Set-up Caller')}}" />
                <x-jet-input-error for="order.setter_id" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="closer_id" value="{{ __('Closer') }}" />
                <x-btui-select id="closer_id" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.agent_id" :options="$agents" wire:model.defer="order.agent_id" placeholder="{{__('Select Closer')}}" />
                <x-jet-input-error for="order.agent_id" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="commission_type" value="{{ __('Commission Type') }}" />
                <x-btui-select id="commission_type" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.commission_type" :options="$commission_types" wire:model.defer="order.commission_type" placeholder="{{__('Select Commission Type')}}" />
                <x-jet-input-error for="order.commission_type" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="hs_vid" value="{{ __('Hubspot User ID') }}" />
                <x-jet-input id="hs_vid" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.hs_vid" />
                <x-jet-input-error for="order.hs_vid" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="hs_deal_id" value="{{ __('Hubspot Deal ID') }}" />
                <x-jet-input id="hs_deal_id" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.hs_deal_id" />
                <x-jet-input-error for="order.hs_deal_id" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="sevdesk_user_id" value="{{ __('Sev Desk User ID') }}" />
                <x-jet-input id="sevdesk_user_id" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.sevdesk_user_id" />
                <x-jet-input-error for="order.sevdesk_user_id" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="sevdesk_invoice_id" value="{{ __('SevDesk Invoice ID') }}" />
                <x-jet-input id="sevdesk_invoice_id" type="text" class="mt-1 block w-full form-control shadow-none" disabled={{$isDisabled}} wire:model.defer="order.sevdesk_invoice_id" />
                <x-jet-input-error for="order.sevdesk_invoice_id" class="mt-2" />
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                {{ __($button['submit_response']) }}
            </x-jet-action-message>

            <x-jet-button>
                {{ __($button['submit_text']) }}
            </x-jet-button>
        </x-slot>
    </x-jet-form-section>

    <x-notify-message on="saved" type="success" :message="__($button['submit_response_notyf'])" />
</div>


<script>
    function copyToClipboard() {
        var input = document.getElementById("hs-copy-input");
        var button = document.getElementById("hs-copy-button");
        input.select();
        document.execCommand('copy');

        button.innerText = "copied";
        setTimeout(function(){
          button.innerText = "Copy Url";
        }, 3000);
    }
</script>
