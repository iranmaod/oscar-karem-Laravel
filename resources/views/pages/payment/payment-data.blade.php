<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('All Payments') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment') }}">{{__("Payments")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment') }}">{{__("All Payments")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:table.main name="payment" :model="$order" />
    </div>
</x-app-layout>
