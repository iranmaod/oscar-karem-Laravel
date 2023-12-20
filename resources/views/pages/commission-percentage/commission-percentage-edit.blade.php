<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Edit Commission Percentage') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('commission-percentage') }}">{{__("Commission Percentage")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('commission-percentage') }}">{{__("Edit Percentage")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-commission-percentage action="updateCommissionPercentage" :commissionPercentageId="request()->commissionPercentageId" />
    </div>
</x-app-layout>
