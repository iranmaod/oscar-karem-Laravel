<div>
    <x-data-table :data="$data" :model="$products">
        <x-slot name="head">
            <tr>
                <th><a wire:click.prevent="sortBy('id')" role="button" href="#">
                    {{__("ID")}}
                    @include('components.sort-icon', ['field' => 'id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('elopage_product_id')" role="button" href="#">
                    {{__("Elopage Product Id")}}
                    @include('components.sort-icon', ['field' => 'elopage_product_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('name')" role="button" href="#">
                    {{__("Name")}}
                    @include('components.sort-icon', ['field' => 'name'])
                </a></th>
                <th><a wire:click.prevent="sortBy('status_id')" role="button" href="#">
                    {{__("Status")}}
                    @include('components.sort-icon', ['field' => 'status_id'])
                </a></th>

                <th>{{__("Action")}}</th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach ($products as $product)
                <tr x-data="window.__controller.dataTableController({{ $product->id }})">
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->elopage_product_id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->status->name }}</td>
                    <td class="whitespace-no-wrap row-action--icon hs-ok-data-table-action">
                        <a role="button" href="{{route('product.edit', array('productId' => base64_encode($product->id)) )}}" class="hs-ok-button-edit hs-ok-action-button"><i class="fa fa-16px fa-pen"></i></a>
                        <a role="button" x-on:click.prevent="deleteItem" href="#" class="hs-ok-button-delete hs-ok-action-button"><i class="fa fa-16px fa-trash text-red-500"></i></a>
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table>
</div>
