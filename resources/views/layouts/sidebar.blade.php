<aside id="side_bar" class="side-bar">
    <div class="side-bar-logo">
        <a href="#" class="big_logo">
            <img src="{{ asset('images/logo.svg') }}" alt="asign" />
        </a>
        <a href="#" class="tiny_logo">
            <img src="{{ asset('images/logo_small.png') }}" alt="asign" />
        </a>
    </div>
    <div class="side-bar-links">
        @if (access()->hasAccess('customer.view'))
        <article class="has-submenu {{request()->is('customer*')? 'active up' : 'down' }}" data-redirect="{{ url('customer') }}">
            <img src="{{ asset('icons/profile-2user.svg') }}" alt="All Customer" />
            <label>Customers</label>
            <span>
                <img src="{{ asset(request()->is('customer*')? 'icons/arrow-up.svg'  :  'icons/arrow-down.svg') }}" class="indicator" alt="All Customer1" />
            </span>
        </article>
        @endif
        <div class="article-submenu {{request()->is('customer*')? 'active down' : 'up' }}">
            <a class=" {{(request()->is('customer')  || request()->is('customer/view/*')) ? 'active' : '' }}" href="{{ url('customer') }}">
                <span>All Customers</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="All Customers"><img src="{{ asset('icons/personalcard.svg') }}" /></span>
            </a>
            <a class=" {{(request()->is('customer/artist*')) ? 'active' : '' }}" href="{{ url('customer/artist') }}">
                <span>Artists</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Artists"><img src="{{ asset('icons/blur.svg') }}" /></span>
            </a>
            <a class=" {{(request()->is('customer/business*')) ? 'active' : '' }}" href="{{ url('customer/business') }}">
                <span>Businesses</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Businesses"><img src="{{ asset('icons/3d-cube-scan.svg') }}" /></span>
            </a>
            <a class=" {{(request()->is('customer/collector*')) ? 'active' : '' }}" href="{{ url('customer/collector') }}">
                <span>Collectors</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Collectors"><img src="{{ asset('icons/coin.svg') }}" /></span>
            </a>
        </div>
        @if (access()->hasAccess('authentication-request.view') || access()->hasAccess('label-requests.view') || access()->hasAccess('inspection-request.view'))
        <article class="has-submenu {{request()->is('protect-request*', 'protect-approved*')? 'active  up' : 'down' }}" data-redirect="index.php">
            <img src="{{ asset('icons/protect_plus.svg') }}" alt="Asign Protect+" />
            <label>Asign Protect+</label>
            <span>
                <img src="{{ asset(request()->is('protect-request*', 'protect-approved*')? 'icons/arrow-up.svg' : 'icons/arrow-down.svg') }}" class="indicator" alt="Asign Protect+" />
            </span>
        </article>
        @endif
        <div class="article-submenu {{request()->is('protect-request*', 'protect-approved*')? 'active down' : 'up' }}">
            <a class="  {{(request()->is('protect-request*')) ? 'active' : '' }}" href="{{ url('protect-request') }}">
                <span>Asign Protect+ Requested</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Asign Protect+ Requested"><img src="{{ asset('icons/personalcard.svg') }}" /></span>
            </a>
            <a class="  {{(request()->is('protect-approved*')) ? 'active' : '' }}" href="{{ url('protect-approved') }}">
                <span>Asign Protect+ Approved</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Asign Protect+ Approved"><img src="{{ asset('icons/blur.svg') }}" /></span>
            </a>
        </div>
        {{-- @if (access()->hasAccess('authentication-request.view') || access()->hasAccess('label-requests.view') || access()->hasAccess('inspection-request.view'))
            <article class="has-redirect {{(request()->is('protect-request*')) ? 'active' : '' }}"
        data-redirect="{{ url('protect-request') }}">
        <img src="{{ asset('icons/protect_plus.svg') }}" alt="Asign Protect+" />
        <label>Asign Protect+</label>
        <span></span>
        </article>
        @endif --}}
        @if (access()->hasAccess('stock-overview.view'))
        <article class="has-redirect {{(request()->is('label-stock*')) ? 'active' : '' }}" data-redirect="{{ url('label-stock') }}">
            <img src="{{ asset('icons/label.svg') }}" alt="Stock" />
            <label>Label Stock</label>
            <span></span>
        </article>
        @endif
        @php
        $stock_management = ['purchase-orders', 'purchase-orders/*', 'stock-transfer-orders', 'stock-transfer-orders/*', 'label-damaged', 'label-damaged/*', 'stock-check', 'stock-check/*', 'goods-received-notes', 'goods-received-notes/*', 'label-request', 'label-request/*', 'label-issues', 'label-issues/*', 'label-return','label-return/*'];
        $stock_management_active = false;
        foreach ($stock_management as $key => $value) {
        if(request()->is($value)){
        $stock_management_active = true;
        break;
        }
        }

        @endphp
        @if(access()->hasAccess('purchase-order.view')
        || access()->hasAccess('stock-transfer-order.view')
        || access()->hasAccess('label-request.view')
        || access()->hasAccess('damages.view')
        || access()->hasAccess('stock-check.view')
        || access()->hasAccess('goods-received-note.view'))
        <article class="has-submenu {{$stock_management_active ? 'active up' : 'down' }}" data-redirect="">
            <img src="{{ asset('icons/clipboard-text.svg') }}" alt="All Customer" />
            <label>Label Management</label>
            <span>
                <img src="{{ asset($stock_management_active ? 'icons/arrow-up.svg'  :  'icons/arrow-down.svg') }}" class="indicator" alt="Stock Management" />
            </span>
        </article>
        @endif
        <div class="article-submenu {{$stock_management_active ? 'active down' : 'up' }}">
            @if (access()->hasAccess('purchase-order.view'))
            <a class="{{(request()->is('purchase-orders') || request()->is('purchase-orders/*')) ? 'active' : '' }}" href="{{ url('purchase-orders') }}">
                <span>Purchase Orders</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Purchase Orders"><img src="{{ asset('icons/personalcard.svg') }}" /></span>
            </a>
            @endif
            @if (access()->hasAccess('stock-transfer-order.view'))
            <a class="{{(request()->is('stock-transfer-orders') || request()->is('stock-transfer-orders/*')) ? 'active' : '' }}" href="{{ url('stock-transfer-orders') }}">
                <span>Stock Transfer Orders</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Stock Transfer Orders"><img src="{{ asset('icons/personalcard.svg') }}" /></span>
            </a>
            @endif
            @if (access()->hasAccess('label-request.view')|| access()->hasAccess('label-issue.view') || access()->hasAccess('label-return.view'))
            <a class="has_right_menu {{(request()->is('label-request','label-request/*', 'label-issues/*', 'label-issues', 'label-return', 'label-return/*')) ? 'active' : '' }}" href="#">
                <span>Label Requests</span>
                <ul class="subnav">
                    @if (access()->hasAccess('label-request.view'))
                    <li class="{{(request()->is('label-request','label-request/*')) ? 'active' : '' }}" data-redirect="{{ url('/label-request') }}">Label Requests
                    </li>
                    @endif
                    @if (access()->hasAccess('label-issue.view'))
                    <li class="{{(request()->is('label-issues', 'label-issues/*')) ? 'active' : '' }}" data-redirect="{{url('/label-issues')}}">Label Issue
                    </li>
                    @endif
                    @if (access()->hasAccess('label-return.view'))
                    <li class="{{(request()->is('label-return','label-return/*')) ? 'active' : '' }}" data-redirect="{{url('/label-return')}}">Label Return
                    </li>
                    @endif
                </ul>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="City"><img src="{{ asset('icons/crown-1.svg') }}" /></span>
            </a>
            @endif
            @if (access()->hasAccess('damages.view'))
            <a class="{{( request()->is('label-damaged') || request()->is('label-damaged/*')) ? 'active' : '' }}" href="{{ url('label-damaged') }}">
                <span>Damaged labels</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Damaged labels"><img src="{{ asset('icons/personalcard.svg') }}" /></span>
            </a>
            @endif
            @if (access()->hasAccess('stock-check.view'))
            <a class="{{( request()->is('stock-check') || request()->is('stock-check/*')) ? 'active' : '' }}" href="{{ url('stock-check') }}">
                <span>Stock Check</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Stock Check"><img src="{{ asset('icons/personalcard.svg') }}" /></span>
            </a>
            @endif
            @if (access()->hasAccess('goods-received-note.view'))
            <a class="{{( request()->is('goods-received-notes') || request()->is('goods-received-notes/*')) ? 'active' : '' }}" href="{{ url('goods-received-notes') }}">
                <span>Goods Received Notes</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Goods Received Notes"><img src="{{ asset('icons/personalcard.svg') }}" /></span>
            </a>
            @endif
        </div>
        @if (access()->hasAccess('user.view'))
        <article class="has-redirect {{(request()->is('user-management*')) ? 'active' : '' }}" data-redirect="{{ url('user-management') }}">
            <img src="{{ asset('icons/profile.svg') }}" alt="All Customer" />
            <label>Asign Team</label>
            <span></span>
        </article>
        @endif

        <!-- @if (access()->hasAccess('user.view'))
            <article class="has-redirect {{(request()->is('settings*')) ? 'active' : '' }}"
                 data-redirect="{{ url('settings') }}">
            <img src="{{ asset('icons/profile.svg') }}" alt="settings img"/>
            <label>Settings</label>
            <span></span>
        </article>

        @endif -->

        <!-- <article class="has-submenu {{$stock_management_active ? 'active up' : 'down' }}" data-redirect="">
            <img src="{{ asset('icons/clipboard-text.svg') }}" alt="All Customer"/>
            <label>Artwork Request</label>
            <span>
                <img src="{{ asset($stock_management_active ? 'icons/arrow-up.svg'  :  'icons/arrow-down.svg') }}"
                     class="indicator" alt="Stock Management"/>
            </span>
        </article>

        <div class="article-submenu {{$stock_management_active ? 'active down' : 'up' }}">

            <a class="{{(request()->is('image-request/imageRequestList*')) ? 'active' : '' }}"
               href="{{ route('image-request.imageRequestList') }}">
                <span>Image Request</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Image Request"><img
                        src="{{ asset('icons/personalcard.svg') }}"/></span>
            </a>


            <a class="{{(request()->is('image-request/priceRequestList*') || request()->is('stock-transfer-orders/*')) ? 'active' : '' }}"
               href="{{ route('image-request.priceRequestList') }}">
                <span>Price Request</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Stock Transfer Orders"><img
                        src="{{ asset('icons/personalcard.svg') }}"/></span>
            </a>

            <a class="{{(request()->is('image-request/viewRequestList*') || request()->is('stock-transfer-orders/*')) ? 'active' : '' }}"
               href="{{ route('image-request.viewRequestList') }}">
                <span>Private View Request</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Stock Transfer Orders"><img
                        src="{{ asset('icons/personalcard.svg') }}"/></span>
            </a>

            <a class="{{(request()->is('image-request/offerRequestList*') || request()->is('stock-transfer-orders/*')) ? 'active' : '' }}"
               href="{{ route('image-request.offerRequestList') }}">
                <span>Offer Request</span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Stock Transfer Orders"><img
                        src="{{ asset('icons/personalcard.svg') }}"/></span>
            </a>


        </div> -->

        @if(access()->hasAccess('master.view') || access()->hasAccess('role.view'))
        <article class="has-submenu {{request()->is('masters*')? 'active  up' : 'down' }}" data-redirect="index.php">
            <img src="{{ asset('icons/crown-1.svg') }}" alt="All Customer" />
            <label>Master</label>
            <span>
                <img src="{{ asset(request()->is('masters*')? 'icons/arrow-up.svg' : 'icons/arrow-down.svg') }}" class="indicator" alt="Master" />
            </span>
        </article>
        @endif

        <div class="article-submenu {{request()->is('masters*')? 'active  down' : 'up'}}">
            @if (access()->hasAccess('master.view'))
            <a class="has_right_menu {{(request()->is('masters/artist*','masters/fair*','masters/gallery*','masters/house*')) ? 'active' : '' }}" href="javascript:void(0)">
                <span>Art Ecosystem</span>
                <ul class="subnav">
                    <li class="{{(request()->is('masters/artist*')) ? 'active' : '' }}" data-redirect="{{url('masters/artist')}}">Artist
                    </li>
                    <li class="{{(request()->is('masters/house*')) ? 'active' : '' }}" data-redirect="{{url('masters/house')}}">Auction House
                    </li>
                    <li class="{{(request()->is('masters/fair*')) ? 'active' : '' }}" data-redirect="{{url('masters/fair')}}">Fairs
                    </li>
                    <li class="{{(request()->is('masters/gallery*')) ? 'active' : '' }}" data-redirect="{{url('masters/gallery')}}">Gallery
                    </li>
                </ul>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="City"><img src="{{ asset('icons/crown-1.svg') }}" /></span>
            </a>
            <a class="has_right_menu {{(request()->is('masters/object-type*','masters/size*','masters/price*','masters/medium*','masters/surface-medium*','masters/measurement-type*','masters/shape*','masters/technique*','masters/style*','masters/subject*','masters/movement*','masters/era*','masters/period*')) ? 'active' : '' }}" href="javascript:void(0)">
                <span>Artwork Details</span>
                <ul class="subnav">
                    <li class="{{(request()->is('masters/era*')) ? 'active' : '' }}" data-redirect="{{url('masters/era')}}">Eras
                    </li>
                    <li class="{{(request()->is('masters/measurement-type*')) ? 'active' : '' }}" data-redirect="{{url('masters/measurement-type')}}">Measurement type
                    </li>
                    <li class="{{(request()->is('masters/medium*')) ? 'active' : '' }}" data-redirect="{{url('masters/medium')}}">Medium
                    </li>
                    <li class="{{(request()->is('masters/movement*')) ? 'active' : '' }}" data-redirect="{{url('masters/movement')}}">Movement
                    </li>
                    <li class="{{(request()->is('masters/object-type*')) ? 'active' : '' }}" data-redirect="{{url('masters/object-type')}}">Object Type
                    </li>
                    <li class="{{(request()->is('masters/period*')) ? 'active' : '' }}" data-redirect="{{url('masters/period')}}">Period
                    </li>
                    <li class="{{(request()->is('masters/price*')) ? 'active' : '' }}" data-redirect="{{url('masters/price')}}">Price
                    </li>
                    <li class="{{(request()->is('masters/shape*')) ? 'active' : '' }}" data-redirect="{{url('masters/shape')}}">Shape
                    </li>
                    <li class="{{(request()->is('masters/size*')) ? 'active' : '' }}" data-redirect="{{url('masters/size')}}">Size
                    </li>
                    <li class="{{(request()->is('masters/style*')) ? 'active' : '' }}" data-redirect="{{url('masters/style')}}">Style
                    </li>
                    <li class="{{(request()->is('masters/subject*')) ? 'active' : '' }}" data-redirect="{{url('masters/subject')}}">Subject
                    </li>
                    <li class="{{(request()->is('masters/surface-medium*')) ? 'active' : '' }}" data-redirect="{{url('masters/surface-medium')}}">Surface
                    </li>
                    <li class="{{(request()->is('masters/technique*')) ? 'active' : '' }}" data-redirect="{{url('masters/technique')}}">Technique
                    </li>


                </ul>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="City"><img src="{{ asset('icons/crown-1.svg') }}" /></span>
            </a>
            <a class="has_right_menu {{(request()->is('masters/acquisition-type*','masters/insurance-type*','masters/valuation/type*','masters/valuation/basis*','masters/valuation/approach*','masters/valuation/status*')) ? 'active' : '' }}" href="javascript:void(0)">
                <span>Provenance Details </span>
                <ul class="subnav">
                    <li class="{{(request()->is('masters/acquisition-type*')) ? 'active' : '' }}" data-redirect="{{url('masters/acquisition-type')}}">Acquisition Type
                    </li>
                    <li class="{{(request()->is('masters/insurance-type*')) ? 'active' : '' }}" data-redirect="{{url('masters/insurance-type')}}">Insurance Type
                    </li>
                    <li class="{{(request()->is('masters/valuation/approach*')) ? 'active' : '' }}" data-redirect="{{url('masters/valuation/approach')}}">Valuation Approach
                    </li>
                    <li class="{{(request()->is('masters/valuation/basis*')) ? 'active' : '' }}" data-redirect="{{url('masters/valuation/basis')}}">Valuation Basis
                    </li>
                    <li class="{{(request()->is('masters/valuation/status*')) ? 'active' : '' }}" data-redirect="{{url('masters/valuation/status')}}">Valuation Status
                    </li>
                    <li class="{{(request()->is('masters/valuation/type*')) ? 'active' : '' }}" data-redirect="{{url('masters/valuation/type')}}">Valuation Type
                    </li>
                </ul>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="City"><img src="{{ asset('icons/crown-1.svg') }}" /></span>
            </a>

            <a class="has_right_menu {{(request()->is('masters/branch-office*','masters/country*','masters/city*','masters/state*')) ? 'active' : '' }}" href="javascript:void(0)">
                <span>Location </span>
                <ul class="subnav">
                    <li class="{{(request()->is('masters/branch-office*')) ? 'active' : '' }}" data-redirect="{{url('masters/branch-office')}}">Asign Locations
                    </li>
                    <li class="{{(request()->is('masters/city*')) ? 'active' : '' }}" data-redirect="{{url('masters/city')}}">City
                    </li>
                    <li class="{{(request()->is('masters/country*')) ? 'active' : '' }}" data-redirect="{{url('masters/country')}}">Country
                    </li>
                    <li class="{{(request()->is('masters/state*')) ? 'active' : '' }}" data-redirect="{{url('masters/state')}}">State
                    </li>

                </ul>
            </a>


            <a class="has_right_menu {{(request()->is('masters/manufacturer*','masters/transfer-reason*','masters/damage-type*','masters/stockcheck-type*')) ? 'active' : '' }}" href="javascript:void(0)">
                <span>Label Management </span>
                <ul class="subnav">
                    <li class="{{(request()->is('masters/damage-type*')) ? 'active' : '' }}" data-redirect="{{url('masters/damage-type')}}">Label Damage Type
                    </li>
                    <li class="{{(request()->is('masters/manufacturer*')) ? 'active' : '' }}" data-redirect="{{url('masters/manufacturer')}}">Manufacturer Name
                    </li>
                    <li class="{{(request()->is('masters/stockcheck-type*')) ? 'active' : '' }}" data-redirect="{{url('masters/stockcheck-type')}}">Stock Check Type
                    </li>
                    <li class="{{(request()->is('masters/transfer-reason*')) ? 'active' : '' }}" data-redirect="{{url('masters/transfer-reason')}}">Stock Transfer Reasons
                    </li>
                </ul>
            </a>

            <a class="has_right_menu {{(request()->is('masters/year*', 'masters/currency*')) ? 'active' : '' }}" href="javascript:void(0)">
                <span>Misc </span>
                <ul class="subnav">
                    <li class="{{(request()->is('masters/year*')) ? 'active' : '' }}" data-redirect="{{url('masters/year')}}">Years
                    </li>
                    <li class="{{(request()->is('masters/currency*')) ? 'active' : '' }}" data-redirect="{{url('masters/currency')}}">Currency
                    </li>
                </ul>
            </a>

            <a class="has_right_menu {{(request()->is('masters/advisory-service*','masters/category*','masters/condition-observation*','masters/document-type*','masters/exhibition-type*','masters/genre*','masters/report-condition*',)) ? 'active' : '' }}" href="javascript:void(0)">
                <span>Check </span>
                <ul class="subnav">
                    <li class="{{(request()->is('masters/advisory-service*')) ? 'active' : '' }}" data-redirect="{{url('masters/advisory-service')}}">Advisory Service List
                    </li>
                    <li class="{{(request()->is('masters/category*')) ? 'active' : '' }}" data-redirect="{{url('masters/category')}}">Category
                    </li>
                    <li class="{{(request()->is('masters/condition-observation*')) ? 'active' : '' }}" data-redirect="{{url('masters/condition-observation')}}">Condition Observatione
                    </li>
                    <li class="{{(request()->is('masters/document-type*')) ? 'active' : '' }}" data-redirect="{{url('masters/document-type')}}">Document Type
                    </li>
                    <li class="{{(request()->is('masters/exhibition-type*')) ? 'active' : '' }}" data-redirect="{{url('masters/exhibition-type')}}">Exhibition Types
                    </li>
                    <li class="{{(request()->is('masters/genre*')) ? 'active' : '' }}" data-redirect="{{url('masters/genre')}}">Genre
                    </li>
                    <li class="{{(request()->is('masters/report-condition*')) ? 'active' : '' }}" data-redirect="{{url('masters/report-condition')}}">Report Conditions
                    </li>
                </ul>
            </a>


            {{-- <a class="has_right_menu {{(request()->is('masters/transporter*', 'masters/product*')) ? 'active' : '' }}"
            href="javascript:void(0)">
            <span>Stock </span>
            <ul class="subnav">
                <li class="{{(request()->is('masters/product*')) ? 'active' : '' }}" data-redirect="{{url('masters/product')}}">Product
                </li>
                <li class="{{(request()->is('masters/transporter*')) ? 'active' : '' }}" data-redirect="{{url('masters/transporter')}}">Transporter
                </li>
            </ul>
            </a> --}}

            <a class="has_right_menu {{(request()->is('masters/coverage*'
                ,'masters/rejected-reason*' ,'masters/time*'
                ,'masters/inscription*','masters/product*','masters/site-condition*','masters/transporter*')) ? 'active' : '' }}" href="javascript:void(0)">
                <span>Others</span>
                <ul class="subnav">
                    <li class="{{ request()->is('masters/authenticator-checklist*') ? 'active' : '' }}" data-redirect="{{ url('masters/authenticator-checklist') }}">Authenticator Reason
                    </li>
                    <li class="{{ request()->is('masters/coverage*') ? 'active' : '' }}" data-redirect="{{ url('masters/coverage') }}">Coverage Type
                    </li>
                    <li class="{{ request()->is('masters/gst*') ? 'active' : '' }}" data-redirect="{{ url('masters/gst') }}">GST
                    </li>
                    <li class="{{ request()->is('masters/inscription*') ? 'active' : '' }}" data-redirect="{{ url('masters/inscription') }}">Inscription
                    </li>
                    <li class="{{ request()->is('masters/object-condition*') ? 'active' : '' }}" data-redirect="{{ url('masters/object-condition') }}">Object Condition
                    </li>
                    <li class="{{ request()->is('masters/product*') ? 'active' : '' }}" data-redirect="{{ url('masters/product') }}">Product
                    </li>
                    <li class="{{ request()->is('masters/rejected-reason*') ? 'active' : '' }}" data-redirect="{{ url('masters/rejected-reason') }}">Rejected Reasons
                    </li>
                    <li class="{{ request()->is('masters/represenation-rejectedreason*') ? 'active' : '' }}" data-redirect="{{ url('masters/represenation-rejectedreason') }}">Represent Reasons
                    </li>
                    <li class="{{ request()->is('masters/site-condition*') ? 'active' : '' }}" data-redirect="{{ url('masters/site-condition') }}">Site Condition
                    </li>
                    <li class="{{ request()->is('masters/surface-type*') ? 'active' : '' }}" data-redirect="{{ url('masters/surface-type') }}">Surface Type
                    </li>
                    <li class="{{ request()->is('masters/time*') ? 'active' : '' }}" data-redirect="{{ url('masters/time') }}">Time Zones
                    </li>
                    <li class="{{ request()->is('masters/transporter*') ? 'active' : '' }}" data-redirect="{{ url('masters/transporter') }}">Transporter
                    </li>
                    <li class="{{ request()->is('masters/void-reason*') ? 'active' : '' }}" data-redirect="{{ url('masters/void-reason') }}">Void Reason
                    </li>
                </ul>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="City"><img src="{{ asset('icons/crown-1.svg') }}" /></span>
            </a>
            @endif

            @if(access()->hasAccess('role.view'))
            <a class="has_right_menu {{(request()->is('masters/role-management*')) ? 'active' : '' }}" href="{{url('masters/role-management')}}">
                <span>Roles </span>
                <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="City">
                    <img src="{{ asset('icons/crown-1.svg') }}" /></span>
            </a>
            @endif
        </div>
    </div>
    <div class="side-bar-user">
        <div class="profile-widget widget-md">
            <label>
                <img src="{{ asset('images/noimage.png') }}" alt="Priyadarshini Patel" />
                <!-- <span class="status"></span> -->
            </label>
            <div>
                <h4 class="extra-content">{{auth()->user()->name}}</h4>
                <h5>{{auth()->user()->role?->name ?? ''}} @if(isset(auth()->user()->branch_name) && auth()->user()->branch_name !=NULL)
                    ( {{auth()->user()->branch_name}} )
                    @endif</h5>
            </div>
            <div class="open-profile">
                <div class="d-flex align-items-center justify-content-end dot-dropdown">
                    <div class="btn-group dropdown">
                        <span class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class='bx bx-dots-horizontal-rounded fs-4'></i></span>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" type="button">
                                <a href="#">
                                    <span>My Profile</span>
                                </a>
                            </button>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();">Logout</a>
                            <form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>