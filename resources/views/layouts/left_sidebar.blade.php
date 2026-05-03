<div class="vertical-menu">
    {{--
    @Author: Anwarul
    @Date: 2025-11-17 17:12:23
 @LastEditors: Anwarul
 @LastEditTime: 2026-04-21 15:36:42
    @Description: Innova IT
    --}}
    <div data-simplebar class="h-100">
        @auth()
            <!--- Sidemenu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="metismenu list-unstyled" id="side-menu">
                    {{-- Dashboard --}}
                    <li>
                        <a href="{{ route('dashboard') }}" class="waves-effect">
                            <i class="ri-dashboard-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    {{-- User Management --}}
                    @canany(['users.index', 'roles.index', 'permissions.index', 'admin.index'])
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="ri-account-circle-line"></i>
                                <span>User</span>
                                <i class="float-right has-arrow"></i>
                            </a>
                            <ul class="sub-menu ml-3" aria-expanded="false">
                                @can(['admin.index'])
                                    <li><a class="nav-link" href="{{ route('admin.index') }}"><i class="ri-user-add-line"></i>
                                            Manage Admin</a></li>
                                @endcan
                                @can(['users.index'])
                                    <li><a class="nav-link" href="{{ route('users.index') }}"><i class="ri-user-add-line"></i>
                                            Manage Users</a></li>
                                @endcan
                                @can(['permissions.index'])
                                    <li><a class="nav-link" href="{{ route('permissions.index') }}"><i class="ri-lock-2-line"></i>
                                            Manage Permission</a></li>
                                @endcan
                                @can(['roles.index'])
                                    <li><a class="nav-link" href="{{ route('roles.index') }}"><i class="ri-share-line"></i> Manage
                                            Role</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany


                    {{-- Transaction Management --}}
                    @canany(['transaction.index'])
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="ri-exchange-dollar-line"></i>
                                <span>Transactions</span>
                                <i class="float-right has-arrow"></i>
                            </a>
                            <ul class="sub-menu ml-3" aria-expanded="false">
                                <li>
                                    <a class="nav-link" href="{{ route('transaction.index', ['status' => 'Paid']) }}">
                                        <i class="ri-checkbox-circle-line"></i> Paid Transactions
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('transaction.index', ['status' => 'Others']) }}">
                                        <i class="ri-time-line"></i> Other Transactions
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcanany



                    {{-- General Settings (Sliders, Faqs, Important Link) --}}
                    @canany(['slider.index', 'faq.index', 'important_link.index'])
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="ri-settings-3-line"></i>
                                <span>General Settings</span>
                                <i class="float-right has-arrow"></i>
                            </a>
                            <ul class="sub-menu ml-3" aria-expanded="false">
                                @can(['setting.index'])
                                    <li>
                                        <a href="{{ route('setting.index') }}" class="waves-effect">
                                            <i class="ri-settings-2-line"></i>
                                            <span>Settings</span>
                                        </a>
                                    </li>
                                @endcan
                                @can(['slider.index'])
                                    <li><a href="{{ route('slider.index') }}"><i class="ri-window-2-line"></i> Sliders</a></li>
                                @endcan
                                @can(['faq.index'])
                                    <li><a href="{{ route('faq.index') }}"><i class="ri-questionnaire-fill"></i> Faqs</a></li>
                                @endcan
                                @can(['important_link.index'])
                                    <li><a href="{{ route('important_link.index') }}"><i class="ri-external-link-fill"></i>
                                            Important Link</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    {{-- Pages --}}
                    @can(['page.index'])
                        <li>
                            <a href="{{ route('page.index') }}" class="waves-effect">
                                <i class="ri-pages-line"></i>
                                <span>Pages</span>
                            </a>
                        </li>
                    @endcan

                    {{-- Blog --}}
                    @canany(['blog_category.index', 'blog.index'])
                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                <i class="ri-book-line"></i>
                                <span>Blog</span>
                                <i class="float-right has-arrow"></i>
                            </a>
                            <ul class="sub-menu ml-3" aria-expanded="false">
                                @can('blog_category.index')
                                    <li><a href="{{ route('blog_category.index') }}"><i class="ri-book-mark-line"></i> Blog
                                            Categories</a></li>
                                @endcan
                                @can('blog.index')
                                    <li><a href="{{ route('blog.index') }}"><i class="ri-file-list-line"></i> Blog</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany



                    {{-- Contact Messages --}}
                    @can(['contact_message.index'])
                        <li>
                            <a href="{{ route('contact_message.index') }}" class="waves-effect">
                                <i class="ri-mail-line"></i>
                                <span>Contact Messages</span>
                            </a>
                        </li>
                    @endcan
                    @can(['setting.index'])
                        <li>
                            <a href="{{ route('setting.index') }}" class="waves-effect">
                                <i class="ri-settings-line"></i>
                                <span>System Settings</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </div>
            <!-- Sidebar -->
        @endauth
    </div>
</div>
