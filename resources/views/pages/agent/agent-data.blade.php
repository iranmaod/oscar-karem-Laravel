<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('All Agents') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('agent') }}">{{__("Agent")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('agent') }}">{{__("All Agents")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:table.main name="agent" :model="$agent" />
    </div>
</x-app-layout>
