<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('All Due Installments') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment') }}">{{__("Installments")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('payment') }}">{{__("All Due Installments")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:table.main name="installment" :model="$installment" />
    </div>
</x-app-layout>
