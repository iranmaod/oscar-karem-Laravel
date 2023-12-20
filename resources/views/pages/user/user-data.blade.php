<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('All User') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="#">{{__("User")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('user') }}">{{__("All User")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:table.main name="user" :model="$user" />
    </div>
</x-app-layout>
