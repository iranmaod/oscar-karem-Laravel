<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Add New Agent') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('commission') }}">{{__("Agent")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('commission') }}">{{__("Add New Agent")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-agent action="createAgent" :hubspotId="request()->hubspotId" />
    </div>
</x-app-layout>
