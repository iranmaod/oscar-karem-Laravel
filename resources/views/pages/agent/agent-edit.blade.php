<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Edit Agent') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('agent') }}">{{__("Agent")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('agent') }}">{{__("Edit Agent")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-agent action="updateAgent" :agentId="request()->agentId" />
    </div>
</x-app-layout>
