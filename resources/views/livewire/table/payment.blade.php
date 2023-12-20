<div>
    <x-payment-data-table :data="$data" :model="$orders">
        <x-slot name="head">
            <tr>
                <th><a wire:click.prevent="sortBy('order_id')" role="button" href="#">
                    {{__("Order")}}
                    @include('components.sort-icon', ['field' => 'order_id'])
                </a></th>
                <th><a role="button" href="#">
                    {{__("Name")}}
                </a></th>
                <th><a role="button" href="#">
                    {{__("Email")}}
                </a></th>
                <th><a role="button" href="#">
                    {{__("Product")}}
                </a></th>
                <th><a role="button" href="#">
                    {{__("Status")}}
                </a></th>
                <th><a role="button" href="#">
                    {{__("Method")}}

                </a></th>
                <th><a role="button" href="#">
                    {{__("Plan")}}
                </a></th>
                <th><a wire:click.prevent="sortBy('payment_start_count')" role="button" href="#">
                    {{__("Payment Count")}}
                </a></th>
                <th><a wire:click.prevent="sortBy('created_at')" role="button" href="#">
                    {{__("Date Created")}}
                </a></th>
                <th>{{("Action")}}</th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach ($orders as $order)
                <tr x-data="window.__controller.dataTableController({{ $order->id }})">
                    @php
                    @endphp
                    <td>{{ @$order->id ? '#'.@$order->id  : ''}}</td>
                    <td>{{ @$order->firstname}} {{ @$order->lastname}}</td>
                    <td>{{ @$order->email}}</td>
                    <td>{{ @$order->product->name }}</td>
                    <td>{{ @$order->status->name }}</td>
                    <td>{{ @$order->payment_method->name }}</td>
                    <td>{{ $order->payment_type == 'installment' ? "Initial Payment & ".@$order->installment->name : "One Time Payment"  }}</td>
                    <td>{{ $order->transaction->where('status','completed')->count() .'/'.($order->payment_type == 'installment' ? $order->installment->billing_threshold + 1 : 1) }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="whitespace-no-wrap row-action--icon hs-ok-data-table-action">
                        <a role="button" href="{{route('payment.view', array('orderId' => base64_encode($order->id) ))}}" class="hs-ok-button-edit hs-ok-action-button"><i class="fa fa-16px fa-desktop"></i></a>
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-payment-data-table>
</div>
