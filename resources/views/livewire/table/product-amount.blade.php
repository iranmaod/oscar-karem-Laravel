<div>
    <x-amount-data-table :data="$data" :model="$productAmounts">
        <x-slot name="head">
            <tr>
                <th><a wire:click.prevent="sortBy('id')" role="button" href="#">
                    {{__("ID")}}
                    @include('components.sort-icon', ['field' => 'id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('elopage_product_id')" role="button" href="#">
                    {{__("Product")}}
                    @include('components.sort-icon', ['field' => 'elopage_product_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('installment_id')" role="button" href="#">
                    {{__("Installment")}}
                    @include('components.sort-icon', ['field' => 'installment_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('amount')" role="button" href="#">
                    {{__("Amount")}}
                    @include('components.sort-icon', ['field' => 'amount'])
                </a></th>
                <th>{{__("Action")}}</th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach ($productAmounts as $amount)
                <tr x-data="window.__controller.dataTableController({{ $amount->id }})">
                    <td>{{ $amount->id }}</td>
                    <td>{{ @$amount->product->name }}</td>
                    <td>{{ $amount->installment_id == 0 ? __("One Time Payment") : __('Initial Amount +'). @$amount->installment->name }}</td>
                    <td>{{ hs_ok_money_format($amount->amount) }}</td>
                    <td class="whitespace-no-wrap row-action--icon hs-ok-data-table-action">
                        <a role="button" href="{{route('product-amount.edit', array('productAmountId' => base64_encode($amount->id)) )}}" class="hs-ok-button-edit hs-ok-action-button"><i class="fa fa-16px fa-pen"></i></a>
                        <a role="button" x-on:click.prevent="deleteItem" href="#" class="hs-ok-button-delete hs-ok-action-button"><i class="fa fa-16px fa-trash text-red-500"></i></a>
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-amount-data-table>
</div>
