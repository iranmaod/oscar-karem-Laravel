<div>
    <x-data-table :data="$data" :model="$installments">
        <x-slot name="head">
            <tr>
                <th><a wire:click.prevent="sortBy('id')" role="button" href="#">
                    {{__("ID")}}
                    @include('components.sort-icon', ['field' => 'id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('order_id')" role="button" href="#">
                    {{__("Order ID")}}
                    @include('components.sort-icon', ['field' => 'order_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('payment_state_id')" role="button" href="#">
                    {{__("Deal ID")}}
                    @include('components.sort-icon', ['field' => 'payment_state_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('payment_method_id')" role="button" href="#">
                    {{__("Due Date")}}
                    @include('components.sort-icon', ['field' => 'payment_method_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('installment_id')" role="button" href="#">
                    {{__("Plan")}}
                    @include('components.sort-icon', ['field' => 'installment_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('payment_start_count')" role="button" href="#">
                    {{__("Payment Count")}}
                    @include('components.sort-icon', ['field' => 'payment_start_count'])
                </a></th>
                <th>{{__("Action")}}</th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach ($installments as $installment)
                <tr x-data="window.__controller.dataTableController({{ $installment->id }})">
                    <td>{{ $installment->id }}</td>
                    <td>{{ @$installment->order->id ? '#'.@$payment->order->id  : ''}}</td>
                    <td>{{ __(@$installment->state->name) }}</td>
                    <td>{{ __(@$payment->method->name) }}</td>
                    <td>{{ __(@$payment->installment->name) }}</td>
                    <td>{{ @$payment->payment_start_count .'/'.@$payment->payment_end_count }}</td>
                    <td class="whitespace-no-wrap row-action--icon hs-ok-data-table-action">
                        <a role="button" href="{{route('order.edit', array('orderId' => $installment->order->id ))}}" class="hs-ok-button-edit hs-ok-action-button"><i class="fa fa-16px fa-pen"></i> View Order</a>
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-data-table>
</div>
