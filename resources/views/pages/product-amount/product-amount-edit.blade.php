<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('Edit Product Amount') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
            <div class="breadcrumb-item"><a href="{{ route('product-amount') }}">{{__("Product Amounts")}}</a></div>
            <div class="breadcrumb-item"><a href="#">{{__("Edit Product Amounts")}}</a></div>
        </div>
    </x-slot>

    <div>
        <livewire:create-product-amount action="updateProductAmount" :productId="request()->productAmountId" />
    </div>
</x-app-layout>
