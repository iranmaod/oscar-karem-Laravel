<div id="form-create">
    <x-jet-form-section :submit="$action" class="mb-4">
        <x-slot name="title">
            {{ __('Payment Method') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Complete the following data and submit to create a new Payment Method') }}
        </x-slot>

        <x-slot name="form">
            <div class="form-group col-span-12 sm:col-span-6">
                <x-jet-label for="name" value="{{ __('Name') }}" />
                <small>{{__('Enter a name for payment method')}}</small>
                <x-jet-input id="name" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="paymentMethod.name" />
                <x-jet-input-error for="paymentMethod.name" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-6">
                <x-jet-label for="slug" value="{{ __('Slug') }}" />
                <small>{{__('Enter a unique slug for payment method')}}</small>
                <x-jet-input id="slug" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="paymentMethod.slug" />
                <x-jet-input-error for="paymentMethod.slug" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="status_id" value="{{ __('Status') }}" />
                <x-btui-select id="status_id" class="mt-1 block w-full form-control shadow-none" name="paymentMethod.status_id" :options="$statuses" wire:model.defer="paymentMethod.status_id" placeholder="{{_('Select Status')}}" />
                <x-jet-input-error for="paymentMethod.status_id" class="mt-2" />
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
