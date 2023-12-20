<div id="form-create">
    <x-jet-form-section :submit="$action" class="mb-4">
        <x-slot name="title">
            {{ __('Order') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Complete the following data and submit to create a new Order') }}
        </x-slot>

        @if($errors->any())
          <h4>{{$errors->first()}}</h4>
        @endif

        <x-slot name="form">
            @if(isset($order->id))
            <div class="form-group col-span-12 md:col-span-12">
              <x-jet-label  value="{{ __('Related Links') }}" />
              <div class="buttons">
                    @if($order->sevdesk_user_id)
                      <a href="https://my.sevdesk.de/#/crm/detail/id/{{$order->sevdesk_user_id}}" class="btn btn-info" target="blank">SevDesk Contact</a>
                    @else
                      <a href="{{route('generate.contact', array('orderId' => base64_encode($order->id)))}}" class="btn btn-primary">Generate SevDesk Contact</a>
                    @endif
                    @if($order->sevdesk_user_id)
                      @if($order->sevdesk_invoice_id)
                        <a href="https://my.sevdesk.de/#/fi/detail/type/RE/id/{{$order->sevdesk_invoice_id}}" class="btn btn-info" target="blank">SevDesk Invoice</a>
                      @else
                        <a href="{{route('generate.invoice', array('orderId' => base64_encode($order->id)))}}" class="btn btn-primary">Generate SevDesk Invoice</a>
                      @endif
                    @endif
                    @if($order->hs_deal_id)
                      <a href="https://app.hubspot.com/contacts/{{env('HUBSPOT_ID')}}/deal/{{$order->hs_deal_id}}" class="btn btn-success" target="blank">Hubspot Deal</a>
                    @else
                      <a href="{{route('generate.deal', array('orderId' => base64_encode($order->id)))}}" class="btn btn-primary">Generate Hubspot Deal</a>
                    @endif
              </div>
            </div>
            @endif
            <div class="form-group col-span-6 md:col-span-6">
                <x-jet-input id="hs_vid" type="hidden" wire:model.defer="order.hs_vid" />
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


            <div class="form-group col-span-6 sm:col-span-12">
                <x-jet-label for="product_id" value="{{ __('Product') }}" />
                <x-btui-select id="product_id" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.elopage_product_id" :options="$products" wire:model.defer="order.elopage_product_id" placeholder="Select Product" />
                <x-jet-input-error for="order.elopage_product_id" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-12">
                <x-jet-label for="amount" value="{{ __('Amount') }}" />
                <x-jet-input id="amount" type="number" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.amount" />
                <x-jet-input-error for="order.amount" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="installment_id" value="{{ __('Installments') }}" />
                <x-btui-select id="installment_id" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.installment_id" :options="$installments" wire:model.defer="order.installment_id" placeholder="Select Installments" />
                <x-jet-input-error for="order.installment_id" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="qty" value="{{ __('Quantity') }}" />
                <x-jet-input id="qty" type="number" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.qty" />
                <x-jet-input-error for="order.qty" class="mt-2" />
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
                <x-btui-select id="country_code" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.country_code" :options="$countries" wire:model.defer="order.country_code" placeholder="Select Country" />
                <x-jet-input-error for="order.country_code" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="plz" value="{{ __('Plz') }}" />
                <x-jet-input id="plz" type="text" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" wire:model.defer="order.plz" />
                <x-jet-input-error for="order.plz" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-12">
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
                <x-btui-select id="order_status_id" class="mt-1 block w-full form-control shadow-none" name="order.order_status_id" :options="$order_statuses" wire:model.defer="order.order_status_id" placeholder="Select Order Status" />
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
                <x-btui-select id="payment_method_id" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.payment_method_id" :options="$payment_methods" wire:model.defer="order.payment_method_id" placeholder="Select Payment Method" />
                <x-jet-input-error for="order.payment_method_id" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="setter_id" value="{{ __('Set-up Caller') }}" />
                <x-btui-select id="setter_id" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.setter_id" :options="$agents" wire:model.defer="order.setter_id" placeholder="Select Set-up Caller" />
                <x-jet-input-error for="order.setter_id" class="mt-2" />
            </div>

            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="closer_id" value="{{ __('Closer') }}" />
                <x-btui-select id="closer_id" class="mt-1 block w-full form-control shadow-none" :disabled="$isDisabled" name="order.agent_id" :options="$agents" wire:model.defer="order.agent_id" placeholder="Select Closer" />
                <x-jet-input-error for="order.agent_id" class="mt-2" />
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
