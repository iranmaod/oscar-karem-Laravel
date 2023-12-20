<div>
    <x-data-table :data="$data" :model="$agents">
        <x-slot name="head">
            <tr>
                <th><a wire:click.prevent="sortBy('id')" role="button" href="#">
                    {{__("ID")}}
                    @include('components.sort-icon', ['field' => 'id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('name')" role="button" href="#">
                    {{__("Hubspot Id")}}
                    @include('components.sort-icon', ['field' => 'hs_vid'])
                </a></th>
                <th><a wire:click.prevent="sortBy('email')" role="button" href="#">
                    {{__("Firstname")}}
                    @include('components.sort-icon', ['field' => 'first_name'])
                </a></th>
                <th><a wire:click.prevent="sortBy('email')" role="button" href="#">
                    {{__("Lastname")}}
                    @include('components.sort-icon', ['field' => 'last_name'])
                </a></th>
                <th><a wire:click.prevent="sortBy('email')" role="button" href="#">
                    {{__("Email")}}
                    @include('components.sort-icon', ['field' => 'email'])
                </a></th>
                <th><a wire:click.prevent="sortBy('email')" role="button" href="#">
                    {{__("Hubspot User ID")}}
                    @include('components.sort-icon', ['field' => 'user_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('commission_payment_type_id')" role="button" href="#">
                    {{__("Payment Method")}}
                    @include('components.sort-icon', ['field' => 'commission_payment_type_id'])
                </a></th>
                <th>{{__("Action")}}</th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach ($agents as $agent)
                <tr x-data="window.__controller.dataTableController({{ $agent->id }})">
                    <td>{{ $agent->id }}</td>
                    <td>{{ $agent->hs_vid }}</td>
                    <td>{{ $agent->first_name }}</td>
                    <td>{{ $agent->last_name }}</td>
                    <td>{{ $agent->email }}</td>
                    <td>{{ $agent->user_id }}</td>
                    <td>{{ __(@$agent->payment_type->name) }}</td>
                    <td class="whitespace-no-wrap row-action--icon hs-ok-data-table-action">
                        <a role="button" href="{{route('agent.edit', array('agentId' => base64_encode($agent->id)) )}}" class="hs-ok-button-edit hs-ok-action-button"><i class="fa fa-16px fa-pen"></i></a>
                        <a role="button" x-on:click.prevent="deleteItem" href="#" class="hs-ok-button-delete hs-ok-action-button"><i class="fa fa-16px fa-trash text-red-500"></i></a>
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table>
</div>
