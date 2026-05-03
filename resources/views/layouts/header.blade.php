<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('dashboard') }}" class="logo logo-light text-decoration-none">

                    <!-- Small logo -->
                    <span class="logo-sm fw-bold text-dark" style="font-size: 22px; letter-spacing: 1px;">

                    </span>

                    <span class="logo-lg fw-bold"
                        style="
                                font-size: 22px;
                                letter-spacing: 1px;
                                color: #00c853;
                            ">
                        Admin <span style="color:#2e7d32;"></span>
                    </span>

                </a>
            </div>
            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle text-dark"></i>
            </button>
            <form class="app-search d-none d-lg-block" id="globalSearchForm" onsubmit="return false;">
                <div class="position-relative">
                    <input type="text" class="form-control" id="globalSearchInput"
                        data-search-url="{{ route('users_search') }}" placeholder="{{ __('Search') }} ..."
                        autocomplete="off">
                    <span class="ri-search-line"></span>
                    <div id="searchResults" class="search-results-dropdown">
                    </div>
                </div>
            </form>
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ri-search-line"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="mb-3 m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="ri-search-line"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>





            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>

            {{-- <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                                  data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-notification-3-line"></i>
                                <span class="noti-dot"></span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-notifications-dropdown">
                                <div class="p-3">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h6 class="m-0"> Notifications </h6>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#!" class="small"> View All</a>
                                        </div>
                                    </div>
                                </div>
                                <div data-simplebar style="max-height: 230px;">
                                    <a href="#" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="avatar-xs me-3">
                                                <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                    <i class="ri-shopping-cart-line"></i>
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <h6 class="mb-1">Your order is placed</h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-1">If several languages coalesce the grammar</p>
                                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <img src="{{ asset('backend')}}/images/users/avatar-3.jpg"
                                                class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                            <div class="flex-1">
                                                <h6 class="mb-1">James Lemire</h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-1">It will seem like simplified English.</p>
                                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <div class="avatar-xs me-3">
                                                <span class="avatar-title bg-success rounded-circle font-size-16">
                                                    <i class="ri-checkbox-circle-line"></i>
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <h6 class="mb-1">Your item is shipped</h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-1">If several languages coalesce the grammar</p>
                                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 3 min ago</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="#" class="text-reset notification-item">
                                        <div class="d-flex">
                                            <img src="{{ asset('backend')}}/images/users/avatar-4.jpg"
                                                class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                            <div class="flex-1">
                                                <h6 class="mb-1">Salena Layfield</h6>
                                                <div class="font-size-12 text-muted">
                                                    <p class="mb-1">As a skeptical Cambridge friend of mine occidental.</p>
                                                    <p class="mb-0"><i class="mdi mdi-clock-outline"></i> 1 hours ago</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2 border-top">
                                    <div class="d-grid">
                                        <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                            <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{-- <img class="rounded-circle header-profile-user" src="{{ asset('backend')}}/images/users/avatar-1.jpg"
                                    alt="Header Avatar"> --}}
                    <span class="d-none d-xl-inline-block ms-1 text-black ">Admin</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i>
                        {{ auth()->user()->name }}</a>
                    {{-- <a class="dropdown-item" href="#"><i class="ri-wallet-2-line align-middle me-1"></i> My Wallet</a>
                                <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end mt-1">11</span><i class="ri-settings-2-line align-middle me-1"></i> Settings</a>
                                <a class="dropdown-item" href="#"><i class="ri-lock-unlock-line align-middle me-1"></i> Lock screen</a> --}}
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="javascript:void(0)"
                        onclick="event.preventDefault(); document.getElementById('logout_form').submit();"><i
                            class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
                    <form method="POST" action="{{ route('logout') }}" id="logout_form">
                        @csrf
                    </form>
                </div>
            </div>

            {{-- <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                                <i class="ri-settings-2-line"></i>
                            </button>
                        </div> --}}

        </div>
    </div>
</header>

<style>
    .search-results-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-height: 400px;
        overflow-y: auto;
        z-index: 1050;
        display: none;
        margin-top: 5px;
    }

    .theme--dark .search-results-dropdown {
        background: #1b1b1b;
        border-color: #302f35;
    }

    .search-results-dropdown.show {
        display: block;
    }

    .wh-link {
        width: 32px;
        height: 32px;
        background-color: #00CD40;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #FFFFFF;
        font-size: 16px;
    }

    .search-result-item {
        padding: 12px 15px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background 0.2s;
    }


    .theme--dark .search-result-item {
        border-color: #302F35;
    }

    .search-result-item:hover {
        background: #f8f9fa;
    }

    .theme--dark .search-result-item:hover {
        background: #835eff;
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .search-result-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .theme--dark .search-result-name {
        color: #FFFFFF;
    }

    .search-result-details {
        font-size: 12px;
        color: #666;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 5px;
    }

    .search-result-badge {
        display: inline-block !important;
        padding: 4px 12px !important;
        background: #007bff !important;
        color: #ffffff !important;
        border-radius: 15px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        margin-bottom: 4px !important;
        white-space: nowrap !important;
    }

    .search-no-results {
        padding: 15px;
        text-align: center;
        color: #999;
    }

    .search-loading {
        padding: 15px;
        text-align: center;
        color: #666;
    }

    [data-theme="dark"] .search-results-dropdown {
        background: #2a3042;
        border-color: #3e4555;
    }

    [data-theme="dark"] .search-result-item {
        border-bottom-color: #3e4555;
    }

    [data-theme="dark"] .search-result-item:hover {
        background: #3e4555;
    }

    [data-theme="dark"] .search-result-name {
        color: #e1e1e1;
    }

    [data-theme="dark"] .search-result-details {
        color: #a6b0cf;
    }

    [data-theme="dark"] .search-result-badge {
        background: #3e4555;
        color: #64b5f6;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('globalSearchInput');
        const searchResults = document.getElementById('searchResults');
        const searchUrl = searchInput.dataset.searchUrl;

        let searchTimeout;

        if (!searchInput) return;

        // Search function
        function performSearch(query) {
            if (!query || query.length < 2) {
                searchResults.classList.remove('show');
                return;
            }

            searchResults.innerHTML = '<div class="search-loading">Searching...</div>';
            searchResults.classList.add('show');

            fetch(searchUrl + '?query=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    if (!Array.isArray(data)) data = [];
                    if (data.length === 0) {
                        searchResults.innerHTML = '<div class="search-no-results">No Users found</div>';
                        return;
                    }

                    let html = '';
                    data.forEach(user => {
                        html += `
                    <div class="search-result-item" onclick="window.location.href='${user.url}'">
                        <div class="search-result-name">
                            ${user.name || 'Unknown'}<br>
                            <small>Phone: ${user.phone || 'N/A'}</small><br>
                            <small>Email: ${user.email || 'N/A'}</small>
                        </div>
                    </div>
                `;
                    });
                    searchResults.innerHTML = html;
                })
                .catch(err => {
                    console.error('Search error:', err);
                    searchResults.innerHTML = '<div class="search-no-results">Error searching users</div>';
                });
        }

        // Input event listener with debounce
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();

            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        // Focus event
        searchInput.addEventListener('focus', function(e) {
            const query = e.target.value.trim();
            const minLength = /^\d+$/.test(query) ? 1 : 2;
            if (query.length >= minLength) {
                performSearch(query);
            }
        });

        // Click outside to close
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.remove('show');
            }
        });

        // Prevent form submission
        document.getElementById('globalSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            return false;
        });
    });
</script>
