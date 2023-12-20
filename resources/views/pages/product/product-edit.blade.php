<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Edit Product') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('product.list') }}">{{__("Products")}}</a></div>
            <div class="breadcrumb-item"><a href="#">{{__("Edit Products")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-product action="updateProduct" :productId="request()->productId" />
    </div>
</x-app-layout>
