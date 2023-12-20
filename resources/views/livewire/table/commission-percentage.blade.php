<div>
    <x-data-table :data="$data" :model="$commissionPercentages">
        <x-slot name="head">
            <tr>
                <th><a wire:click.prevent="sortBy('id')" role="button" href="#">
                    {{__('ID')}}
                    @include('components.sort-icon', ['field' => 'id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('name')" role="button" href="#">
                    {{__('Name')}}
                    @include('components.sort-icon', ['field' => 'name'])
                </a></th>
                <th><a wire:click.prevent="sortBy('commission_employee_type')" role="button" href="#">
                    {{__('Employee Type')}}
                    @include('components.sort-icon', ['field' => 'commission_employee_type'])
                </a></th>
                <th><a wire:click.prevent="sortBy('commission_lead')" role="button" href="#">
                    {{__('Lead Type')}}
                    @include('components.sort-icon', ['field' => 'commission_lead'])
                </a></th>
                <th><a wire:click.prevent="sortBy('commission_payment_type')" role="button" href="#">
                    {{__('Payment Type')}}
                    @include('components.sort-icon', ['field' => 'commission_payment_type'])
                </a></th>
                <th><a wire:click.prevent="sortBy('status_id')" role="button" href="#">
                    {{__('Status')}}
                    @include('components.sort-icon', ['field' => 'status_id'])
                </a></th>
                <th>{{__('Action')}}</th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach ($commissionPercentages as $commissionPercentage)
                <tr x-data="window.__controller.dataTableController({{ $commissionPercentage->id }})">
                    <td>{{ $commissionPercentage->id }}</td>
                    <td>{{ __($commissionPercentage->name) }}</td>
                    <td>{{ __(@$commissionPercentage->employeeType->name) }}</td>
                    <td>{{ __(@$commissionPercentage->leadType->name) }}</td>
                    <td>{{ __(@$commissionPercentage->paymentType->name) }}</td>
                    <td>{{ __(@$commissionPercentage->status->name) }}</td>
                    <td class="whitespace-no-wrap row-action--icon hs-ok-data-table-action">
                        <a role="button" href="{{route('commission-percentage.edit', array('commissionPercentageId' => base64_encode($commissionPercentage->id)) )}}" class="hs-ok-button-edit hs-ok-action-button"><i class="fa fa-16px fa-pen"></i></a>
                        <a role="button" x-on:click.prevent="deleteItem" href="#" class="hs-ok-button-delete hs-ok-action-button"><i class="fa fa-16px fa-trash text-red-500"></i></a>
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table>
</div>
