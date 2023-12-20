<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Add New Product Amount') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('product-amount') }}">{{__("Product Amount")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('product-amount') }}">{{__("Add New Product Anount")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-product-amount action="createProductAmount"  />
    </div>
</x-app-layout>
