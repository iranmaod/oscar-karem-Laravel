<div id="form-create">
    <x-jet-form-section :submit="$action" class="mb-4">
        <x-slot name="title">
            {{ __('Product') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Complete the following data and submit to create a new product') }}
        </x-slot>

        <x-slot name="form">
            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="hs_vid" value="{{ __('Elopage Product Id') }}" />
                <x-jet-input id="hs_vid" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="product.elopage_product_id" />
                <x-jet-input-error for="product.elopage_product_id" class="mt-2" />
            </div>
            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="first_name" value="{{ __('Name') }}" />
                <x-jet-input id="first_name" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="product.name" />
                <x-jet-input-error for="product.name" class="mt-2" />
            </div>
            <div class="form-group col-span-6 sm:col-span-12">
                <x-jet-label for="last_name" value="{{ __('Description') }}" />
                <x-jet-input id="last_name" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="product.description" />
                <x-jet-input-error for="product.description" class="mt-2" />
            </div>

            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="status_id" value="{{ __('Status') }}" />
                <x-btui-select id="status_id" class="mt-1 block w-full form-control shadow-none" name="product.status_id" :options="$statuses" wire:model.defer="product.status_id" placeholder="{{_('Select Status')}}" />
                <x-jet-input-error for="product.status_id" class="mt-2" />
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
