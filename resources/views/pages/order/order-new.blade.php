<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Add New Order') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('order') }}">{{__("Order")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('order') }}">{{__("Add New Order")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-order action="createOrder" :contactId="request()->contactId" />
    </div>
</x-app-layout>
