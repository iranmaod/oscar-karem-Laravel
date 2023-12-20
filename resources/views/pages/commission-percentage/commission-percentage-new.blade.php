<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Add New Commission Percentage') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
        <div class="breadcrumb-item"><a href="{{ route('commission-percentage') }}">{{__("Commission Percentage")}}</a></div>
        <div class="breadcrumb-item"><a href="{{ route('commission-percentage') }}">{{__("Add New Commission Percentage")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-commission-percentage action="createCommissionPercentage"/>
    </div>
</x-app-layout>
