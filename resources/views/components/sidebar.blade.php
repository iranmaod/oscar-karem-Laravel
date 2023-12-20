@php
$links = [
    [
        "href" => "dashboard",
        "text" => __("Dashboard"),
        "is_multi" => false,
    ],
    [
        "href" => [
            [
                "section_text" => __("Contacts"),
                "section_list" => [
                    ["href" => "contact", "text" => __("All Contacts")],
                ],
                "section_icon" => "fas fa-address-book"
            ],
            [
                "section_text" => __("Orders"),
                "section_list" => [
                    ["href" => "order", "text" => __("All Orders")],
                ],
                "section_icon" => "fas fa-shopping-cart"
            ],
            [
                "section_text" => __("Products"),
                "section_list" => [
                    ["href" => "product.list", "text" => __("Elopage Product List")],
                    ["href" => "product", "text" => __("All Products")],
                ],
                "section_icon" => "fas fa-chalkboard-teacher"
            ],
            [
                "section_text" => __("Amounts"),
                "section_list" => [

                    ["href" => "product-amount", "text" => __("All Amounts")],
                ],
                "section_icon" => "fas fa-money-bill-alt"
            ],
            [
                "section_text" => __("Payments"),
                "section_list" => [
                    ["href" => "payment", "text" => __("All Payments")],
                    //["href" => "installment_due", "text" => __("Installments Due")],
                ],
                "section_icon" => "fas fa-money-check-alt"
            ]
        ],
        "text" => __("Order Management"),
        "is_multi" => true,
    ],
    [
        "href" => [
            [
                "section_text" => __("Sales Agents"),
                "section_list" => [
                    ["href" => "agent.list", "text" => __("Hubspot Owners List")],
                    ["href" => "agent", "text" => __("All Sales Agent")],
                    ["href" => "agent.new", "text" => __("New Sales Agent")]
                ],
                "section_icon" => "fas fa-address-book"
            ],
            [
                "section_text" => __("Percentage"),
                "section_list" => [
                    ["href" => "commission-percentage", "text" => __("All Percentage")],
                    ["href" => "commission-percentage.new", "text" => __("New Percentage")]
                ],
                "section_icon" => "fas fa-percentage"
            ],
            [
                "section_text" => __("Commission"),
                "section_list" => [
                    ["href" => "commission", "text" => __("Commission Overview")],
                ],
                "section_icon" => "fas fa-shopping-cart"
            ],
        ],
        "text" => __("Commission System"),
        "is_multi" => true,
    ],
    [
        "href" => [
            [
                "section_text" => __("User"),
                "section_list" => [
                    ["href" => "user", "text" => __("All User")],
                    ["href" => "user.new", "text" => __("New User")]
                ],
                "section_icon" => "fas fa-users"
            ],
            [
                "section_text" => __("Payment Method"),
                "section_list" => [
                    ["href" => "payment.method", "text" => __("All Payment Method")],
                ],
                "section_icon" => "fas fa-money-check-alt"

            ]
        ],
        "text" => __("Admin User"),
        "is_multi" => true,
    ],
];
$navigation_links = array_to_object($links);
@endphp

<div class="hs-ok-sidebar oscar-karem-sidebar main-sidebar">
    <aside id="sidebar-wrapper oscar-karem-sidebar-wrapper hs-ok-sidebar-wrapper">
        <div class="sidebar-brand oscar-karem-sidebar-brand hs-ok-sidebar-brand">
            <a href="{{ route('dashboard') }}"><img class="d-inline-block" width="150px" height="auto" src="{{asset('images/dashboard-logo.png')}}" alt=""></a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm oscar-karem-sidebar-brand-sm hs-ok-sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">
                <img class="d-inline-block" width="32px" height="30.61px" src="{{asset('images/app-logo.png')}}" alt="Dashboard">
            </a>
        </div>
        @foreach ($navigation_links as $link)
        <ul class="sidebar-menu oscar-karem-sidebar-menu hs-ok-sidebar-menu">
            <li class="menu-header">{{ $link->text }}</li>
            @if (!$link->is_multi)
            <li class="{{ Request::routeIs($link->href) ? 'active' : '' }}">
                <a class="nav-link" href="{{ route($link->href) }}"><i class="fas fa-fire"></i><span>{{__('Dashboard')}}</span></a>
            </li>
            @else
                @foreach ($link->href as $section)
                    @php
                    $routes = collect($section->section_list)->map(function ($child) {
                        return Request::routeIs($child->href);
                    })->toArray();

                    $is_active = in_array(true, $routes);
                    @endphp

                    <li class="dropdown {{ ($is_active) ? 'active' : '' }}">
                        <a href="#" class="hs-ok-nav-link nav-link has-dropdown" data-toggle="dropdown"><i class="{{$section->section_icon}}"></i> <span>{{ $section->section_text }}</span></a>
                        <ul class="dropdown-menu hs-ok-dropdown-menu">
                            @foreach ($section->section_list as $child)
                                <li class="{{ Request::routeIs($child->href) ? 'active' : '' }}"><a class="nav-link" href="{{ route($child->href) }}">{{ $child->text }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            @endif
        </ul>
        @endforeach
    </aside>
</div>
