<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('All Orders') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('order') }}">{{__("Orders")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('order') }}">{{__("All Orders")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:table.main name="order" :model="$order" />
    </div>
</x-app-layout>
