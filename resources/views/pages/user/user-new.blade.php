<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Add New User') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="#">{{__("User")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('user') }}">{{__("Add New User")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-user action="createUser" />
    </div>
</x-app-layout>
