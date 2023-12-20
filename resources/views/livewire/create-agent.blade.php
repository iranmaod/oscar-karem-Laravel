<div id="form-create">
    <x-jet-form-section :submit="$action" class="mb-4">
        <x-slot name="title">
            {{ __('Agent') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Complete the following data and submit to create a new agent') }}
        </x-slot>

        <x-slot name="form">
            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="hs_vid" value="{{ __('Hubspot Id') }}" />
                <x-jet-input id="hs_vid" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="agent.hs_vid" />
                <x-jet-input-error for="agent.hs_vid" class="mt-2" />
            </div>
            <div class="form-group col-span-12 sm:col-span-6">
                <x-jet-label for="first_name" value="{{ __('First Name') }}" />
                <x-jet-input id="first_name" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="agent.first_name" />
                <x-jet-input-error for="agent.first_name" class="mt-2" />
            </div>
            <div class="form-group col-span-6 sm:col-span-6">
                <x-jet-label for="last_name" value="{{ __('Last Name') }}" />
                <x-jet-input id="last_name" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="agent.last_name" />
                <x-jet-input-error for="agent.last_name" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="agent.email" />
                <x-jet-input-error for="agent.email" class="mt-2" />
            </div>
            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="user_id" value="{{ __('User ID') }}" />
                <x-jet-input id="user_id" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="agent.user_id" />
                <x-jet-input-error for="agent.agent_id" class="mt-2" />
            </div>
            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="commission_payment_type_id" value="{{ __('Commission Payment Method') }}" />
                <x-btui-select id="commission_payment_type_id" class="mt-1 block w-full form-control shadow-none" name="agent.commission_payment_type_id" :options="$paymentTypes" wire:model.defer="agent.commission_payment_type" placeholder="{{__('Select Commission Payment Method')}}" />
                <x-jet-input-error for="agent.commission_payment_type" class="mt-2" />
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
