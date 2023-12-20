<div>
    <x-order-data-table :data="$data" :model="$orders">
        <x-slot name="head">
            <tr>
                <th><a wire:click.prevent="sortBy('id')" role="button" href="#">
                    {{__('ID')}}
                    @include('components.sort-icon', ['field' => 'id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('firstname')" role="button" href="#">
                    {{__('Firstname')}}
                    @include('components.sort-icon', ['field' => 'firstname'])
                </a></th>
                <th><a wire:click.prevent="sortBy('lastname')" role="button" href="#">
                    {{__('Lastname')}}
                    @include('components.sort-icon', ['field' => 'lastname'])
                </a></th>
                <th><a wire:click.prevent="sortBy('product')" role="button" href="#">
                    {{__('Product')}}
                    @include('components.sort-icon', ['field' => 'product'])
                </a></th>
                <th><a wire:click.prevent="sortBy('email')" role="button" href="#">
                    {{__('Email')}}
                    @include('components.sort-icon', ['field' => 'email'])
                </a></th>
                <th><a wire:click.prevent="sortBy('setter_id')" role="button" href="#">
                    {{__('Setter Agent')}}
                    @include('components.sort-icon', ['field' => 'setter_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('agent_id')" role="button" href="#">
                    {{__('Closer Agent')}}
                    @include('components.sort-icon', ['field' => 'agent_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('status_id')" role="button" href="#">
                    {{__('Status')}}
                    @include('components.sort-icon', ['field' => 'status_id'])
                </a></th>
                <th><a wire:click.prevent="sortBy('created_at')" role="button" href="#">
                    {{__('Date Created')}}
                    @include('components.sort-icon', ['field' => 'created_at'])
                </a></th>
                <th style="width: 180px;">Action</th>
                <th style="width: 180px;">Installments</th>
            </tr>
        </x-slot>
        <x-slot name="body">
            @foreach ($orders as $order)
                <tr x-data="window.__controller.dataTableController({{ $order->id }})">
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->firstname }}</td>
                    <td>{{ $order->lastname }}</td>
                    <td>{{ @$order->product->name }}</td>
                    <td>{{ $order->email }}</td>
                    <td>{{ @$order->setter->full_name }}</td>
                    <td>{{ @$order->agent->full_name }}</td>
                    <td>{{ __(@$order->status->name) }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="whitespace-no-wrap row-action--icon hs-ok-data-table-action">
                        @if($order->status->slug == 'generated' || $order->status->slug == 'pending')
                        @php

                          $paymentLinkData = array('orderId' => base64_encode($order->id), 'key' => base64_encode($order->auth_code));

                          if($order->status->slug == 'pending'){
                            $paymentLinkData['checkout'] = 'resume';
                          }
                        @endphp
                        <input type="hidden" value="{{ route('order.checkout', $paymentLinkData)}}"  id="hs-copy-input-{{$order->id}}">
                        <a role="button"  id="hs-copy-button-{{$order->id}}" onclick="copyToClipboard(event, {{$order->id}})" class="hs-ok-button-view hs-ok-action-button"> <i class="fa fa-16px fa-desktop"></i> </a>
                        @endif
                        <a role="button" href="{{route('order.edit', array('orderId' => base64_encode($order->id)))}}" class="hs-ok-button-edit hs-ok-action-button"><i class="fa fa-16px fa-pen"></i></a>
                        @if($order->status->slug == 'generated' )
                        <a role="button" x-on:click.prevent="deleteItem" href="#" class="hs-ok-button-delete hs-ok-action-button"><i class="fa fa-16px fa-trash text-red-500"></i></a>
                        @endif
                        @if($order->status->slug !== 'generated')
                          <a role="button" href="{{route('order.view', array('orderId' => base64_encode($order->id)))}}" class="hs-ok-button-edit hs-ok-action-button"><i class="fa fa-16px fa-briefcase"></i></a>
                        @endif
                    </td>
                     <!--installments action  -->
                    <td class="whitespace-no-wrap row-action--icon hs-ok-data-table-action">
                         @if($order->payment_type == 'installment')
                          <a role="button" href="{{route('order.view', array('orderId' => base64_encode($order->id)))}}" class="hs-ok-button-edit hs-ok-action-button"><i class="fa fa-16px fa-briefcase"></i></a>
                         @endif
                        
                    </td>
                </tr>
            @endforeach
        </x-slot>
    </x-order-data-table>
</div>

<script>
    function copyToClipboard(e, id) {
        e.preventDefault();
        var input = document.getElementById("hs-copy-input-"+id);
        var button = document.getElementById("hs-copy-button-"+id);
        input.type="text";
        input.select();
        document.execCommand('copy');
        input.type="hidden"

        button.innerHTML  = '<i class="fa fa-16px fa-copy"></i>';
        setTimeout(function(){
          button.innerHTML  = '<i class="fa fa-16px fa-desktop"></i>';
        }, 3000);
    }
</script>
