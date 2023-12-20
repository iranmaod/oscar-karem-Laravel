<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Edit Payments') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment') }}">Payments</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment') }}">Edit Payment</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-payment action="updatePayment" :paymentId="request()->paymentId" />
    </div>
</x-app-layout>
