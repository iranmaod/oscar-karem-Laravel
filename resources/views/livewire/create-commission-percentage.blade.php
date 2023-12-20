<div id="form-create">
    <x-jet-form-section :submit="$action" class="mb-4">
        <x-slot name="title">
            {{ __('Commission Percentage') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Complete the following data and submit to create a new commission percentage') }}
        </x-slot>

        <x-slot name="form">
            <div class="form-group col-span-12 sm:col-span-6">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <x-jet-input id="name" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="commissionPercentage.name" />
                <x-jet-input-error for="commissionPercentage.name" class="mt-2" />
            </div>
            <div class="form-group col-span-12 sm:col-span-6">
                <x-jet-label for="slug" value="{{ __('Slug') }}" />
                <x-jet-input id="slug" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="commissionPercentage.slug" />
                <x-jet-input-error for="commissionPercentage.slug" class="mt-2" />
            </div>


            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="first_lead" value="{{ __('First Lead Percentage') }}" />
                <x-jet-input id="first_lead" type="number" step="any" class="mt-1 block w-full form-control shadow-none" wire:model.defer="commissionPercentage.first_lead" />
                <x-jet-input-error for="commissionPercentage.first_lead" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="second_lead" value="{{ __('Second Lead Percentage') }}" />
                <x-jet-input id="second_lead" type="number" step="any" class="mt-1 block w-full form-control shadow-none" wire:model.defer="commissionPercentage.second_lead" />
                <x-jet-input-error for="commissionPercentage.second_lead" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="third_lead" value="{{ __('Third Lead Percentage') }}" />
                <x-jet-input id="third_lead" type="number" step="any" class="mt-1 block w-full form-control shadow-none" wire:model.defer="commissionPercentage.third_lead" />
                <x-jet-input-error for="commissionPercentage.third_lead" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="fourth_lead" value="{{ __('Fourth Lead Percentage') }}" />
                <x-jet-input id="fourth_lead" type="number" step="any" class="mt-1 block w-full form-control shadow-none" wire:model.defer="commissionPercentage.fourth_lead" />
                <x-jet-input-error for="commissionPercentage.fourth_lead" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="fifth_lead" value="{{ __('Fifth Lead Percentage') }}" />
                <x-jet-input id="fifth_lead" type="number" step="any" class="mt-1 block w-full form-control shadow-none" wire:model.defer="commissionPercentage.fifth_lead" />
                <x-jet-input-error for="commissionPercentage.fifth_lead" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="onward_lead" value="{{ __('Onward Lead Percentage') }}" />
                <x-jet-input id="onward_lead" type="number" step="any" class="mt-1 block w-full form-control shadow-none" wire:model.defer="commissionPercentage.onward_lead" />
                <x-jet-input-error for="commissionPercentage.onward_lead" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="hs_deal_name" value="{{ __('Hubspot Deal Name') }}" />
                <x-jet-input id="hs_deal_name" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="commissionPercentage.hs_deal_name" />
                <x-jet-input-error for="commissionPercentage.onward_lead" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-6">
                <x-jet-label for="commission_employee_type_id" value="{{ __('Employee Type') }}" />
                <x-btui-select id="commission_employee_type_id" class="mt-1 block w-full form-control shadow-none" name="commissionPercentage.employee_type" :options="$employeeTypes" wire:model.defer="commissionPercentage.commission_employee_type" placeholder="{{__('Select Employee Type')}}" />
                <x-jet-input-error for="commissionPercentage.commission_employee_type" class="mt-2" />
            </div>
            <div class="form-group col-span-12 sm:col-span-6">
                <x-jet-label for="commission_lead_id" value="{{ __('Lead Type') }}" />
                <x-btui-select id="commission_lead_id" class="mt-1 block w-full form-control shadow-none" name="commissionPercentage.commission_lead" :options="$leadTypes" wire:model.defer="commissionPercentage.commission_lead" placeholder="{{__('Select Lead Type')}}" />
                <x-jet-input-error for="commissionPercentage.commission_lead" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-6">
                <x-jet-label for="commission_payment_type_id" value="{{ __('Payment Type') }}" />
                <x-btui-select id="commission_payment_type_id" class="mt-1 block w-full form-control shadow-none" name="commissionPercentage.commission_payment_type" :options="$paymentTypes" wire:model.defer="commissionPercentage.commission_payment_type" placeholder="{{__('Select Payment Type')}}" />
                <x-jet-input-error for="commissionPercentage.commission_payment_type" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-6">
                <x-jet-label for="status_id" value="{{ __('Status') }}" />
                <x-btui-select id="status_id" class="mt-1 block w-full form-control shadow-none" name="commissionPercentage.status_id" :options="$statuses" wire:model.defer="commissionPercentage.status_id" placeholder="{{__('Select Status')}}" />
                <x-jet-input-error for="commissionPercentage.status_id" class="mt-2" />
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
