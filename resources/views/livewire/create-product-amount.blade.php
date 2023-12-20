<div id="form-create">
    <x-jet-form-section :submit="$action" class="mb-4">
        <x-slot name="title">
            {{ __('Product Amount') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Complete the following data and submit to create a new product amount') }}
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
            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="elopage_product_id" value="{{ __('Product') }}" />
                <x-btui-select id="elopage_product_id" class="mt-1 block w-full form-control shadow-none" name="productAmount.elopage_product_id" :options="$products" wire:model.defer="productAmount.elopage_product_id" placeholder="{{_('Select Product')}}" />
                <x-jet-input-error for="productAmount.elopage_product_id" class="mt-2" />
            </div>
            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="installment_id" value="{{ __('Installment') }}" />
                <x-btui-select id="installment_id" class="mt-1 block w-full form-control shadow-none" name="productAmount.installment_id" :options="$installments" wire:model.defer="productAmount.installment_id" placeholder="{{_('Select Installment')}}" />
                <x-jet-input-error for="productAmount.installment_id" class="mt-2" />
            </div>
            <div class="form-group col-span-12 sm:col-span-12">
                <x-jet-label for="amount" value="{{ __('Amount') }}" />
                <x-jet-input id="amount" type="text" class="mt-1 block w-full form-control shadow-none" wire:model.defer="productAmount.amount" />
                <x-jet-input-error for="productAmount.amount" class="mt-2" />
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
