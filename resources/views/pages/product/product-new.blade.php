<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Add New Product') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('product') }}">{{__("Product")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('product') }}">{{__("Add New Product")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-product action="createProduct" :elopageProductId="request()->elopageProductId" />
    </div>
</x-app-layout>
