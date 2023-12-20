<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Add New Payment Method') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment.method') }}">{{__("Payment Method")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment.method') }}">{{__("Add New Payment Method")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-payment-method action="createPaymentMethod" />
    </div>
</x-app-layout>
