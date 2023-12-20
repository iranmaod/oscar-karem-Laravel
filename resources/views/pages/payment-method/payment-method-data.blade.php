<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('All Payment Method') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment.method') }}">{{__("Payment Method")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment.method') }}">{{__("All Payment Method")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:table.main name="paymentMethod" :model="$paymentMethod" />
    </div>
</x-app-layout>
