<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Add New Payments') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment') }}">Payments</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment') }}">Add New Payment</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-payment action="createPayment" />
    </div>
</x-app-layout>
