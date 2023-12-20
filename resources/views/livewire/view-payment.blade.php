<div id="form-create">
    <x-jet-form-section :submit="$action" class="mb-4">
        <x-slot name="title">
            {{ __('Payment') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Complete the following data and submit to create a new payment') }}
        </x-slot>

        <x-slot name="form">
          <div class="form-group col-span-6 sm:col-span-3">
              <x-jet-label for="order_id" value="{{ __('Order') }}" />
              <x-btui-select id="order_id" class="mt-1 block w-full form-control shadow-none" name="payment.order_id" :options="$orders" wire:model.defer="payment.order_id" placeholder="{{__('Select Order')}}" />
              <x-jet-input-error for="payment.order_id" class="mt-2" />
          </div>

          <div class="form-group col-span-6 sm:col-span-3">
              <x-jet-label for="payment_state_id" value="{{ __('Payment State') }}" />
              <x-btui-select id="payment_state_id" class="mt-1 block w-full form-control shadow-none" name="payment.payment_state_id" :options="$payment_states" wire:model.defer="payment.payment_state_id" placeholder="{{__('Select Payment State')}}" />
              <x-jet-input-error for="payment.payment_state_id" class="mt-2" />
          </div>

          <div class="form-group col-span-6 sm:col-span-3">
              <x-jet-label for="payment_method_id" value="{{ __('Payment Method') }}" />
              <x-btui-select id="payment_method_id" class="mt-1 block w-full form-control shadow-none" name="order.payment_method_id" :options="$payment_methods" wire:model.defer="payment.payment_method_id" placeholder="{{__('Select Payment Method')}}" />
              <x-jet-input-error for="payment.payment_method_id" class="mt-2" />
          </div>

          <div class="form-group col-span-6 sm:col-span-3">
              <x-jet-label for="installment_id" value="{{ __('Payment Method') }}" />
              <x-btui-select id="installment_id" class="mt-1 block w-full form-control shadow-none" name="payment.installment_id" :options="$installments" wire:model.defer="payment.installment_id" placeholder="{{__('Select Payment Method')}}" />
              <x-jet-input-error for="payment.installment_id" class="mt-2" />
          </div>

          <div class="form-group col-span-6 sm:col-span-3">
              <x-jet-label for="payment_start_count" value="{{ __('Paid Payment Installments') }}" />
              <x-jet-input id="payment_start_count" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="payment.payment_start_count" />
              <x-jet-input-error for="payment.payment_start_count" class="mt-2" />
          </div>

          <div class="form-group col-span-6 sm:col-span-3">
              <x-jet-label for="payment_end_count" value="{{ __('Total Payment Installments') }}" />
              <x-jet-input id="payment_end_count" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="payment.payment_end_count" />
              <x-jet-input-error for="payment.payment_end_count" class="mt-2" />
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
