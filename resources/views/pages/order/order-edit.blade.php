<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Edit Order') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('order') }}">{{__("Order")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('order') }}">{{__("Edit Order")}}</a></div>
        </div>


    </x-slot>

    <div>
        @if (\Session::has('success'))
        <div class="row">
          <div class="col-md-12">
            <div class="alert alert-success alert-dismissible show fade">
                      <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                          <span>×</span>
                        </button>
                        <ul>
                            <li>{!! \Session::get('success') !!}</li>
                        </ul>
                      </div>
            </div>

          </div>
        </div>
        @endif
        <livewire:create-order action="updateOrder" :orderId="request()->orderId" />
    </div>
</x-app-layout>
