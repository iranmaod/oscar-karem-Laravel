<div>
    <x-data-table :data="$data" :model="$paymentMethods">
        <x-slot name="head">
            <tr>
                <th><a wire:click.prevent="sortBy('id')" role="button" href="#">
                    {{__("ID")}}
                    @include('components.sort-icon', ['field' => 'id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('name')" role="button" href="#">
                    {{__("Name")}}
                    @include('components.sort-icon', ['field' => 'name'])
                </a></th>
                <th><a wire:click.prevent="sortBy('slug')" role="button" href="#">
                    {{__("Slug")}}
                    @include('components.sort-icon', ['field' => 'slug'])
                </a></th>
                <th><a wire:click.prevent="sortBy('status_id')" role="button" href="#">
                    {{__("Status")}}
                    @include('components.sort-icon', ['field' => 'status_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('created_at')" role="button" href="#">
                    {{__("Date Created")}}
                    @include('components.sort-icon', ['field' => 'created_at'])
                </a></th>
                <th>{{__("Action")}}</th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach ($paymentMethods as $paymentMethod)
                <tr x-data="window.__controller.dataTableController({{ $paymentMethod->id }})">
                    <td>{{ $paymentMethod->id }}</td>
                    <td>{{ $paymentMethod->name }}</td>
                    <td>{{ $paymentMethod->slug }}</td>
                    <td>{{ @$paymentMethod->status->name }}</td>
                    <td>{{ @$paymentMethod->created_at->format('d M Y H:i') }}</td>
                    <td class="whitespace-no-wrap row-action--icon hs-ok-data-table-action">
                        <a role="button" href="{{route('payment.method.edit', array('paymentMethodId' => $paymentMethod->id))}}" class="hs-ok-button-edit hs-ok-action-button"><i class="fa fa-16px fa-pen"></i></a>
                        <a role="button" x-on:click.prevent="deleteItem" href="#" class="hs-ok-button-delete hs-ok-action-button"><i class="fa fa-16px fa-trash text-red-500"></i></a>
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table>
</div>
