@extends('layouts.app')

@section('content')
    <form action="{{ route('setting.update', 1) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-body">
                <div class="card-body-title">
                    <h5>Site Settings</h5>
                </div>

                <div class="form-content" style="max-width: 1200px; margin:auto">

                    <!-- Tab Navigation -->
                    <div class="text-center">
                        <ul class="nav nav-tabs tc-tabs" id="settingsTab" role="tablist">

                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#basic"
                                        type="button">
                                    <i class="ri-settings-line"></i> {{ __('Basic Info') }}
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#branding" type="button">
                                    <i class="ri-image-line"></i> {{ __('Branding') }}
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#social" type="button">
                                    <i class="ri-share-line"></i> {{ __('Social Media') }}
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#app" type="button">
                                    <i class="ri-smartphone-line"></i> {{ __('Mobile App') }}
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#footer" type="button">
                                    <i class="ri-layout-bottom-line"></i> {{ __('Footer') }}
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#courier" type="button">
                                    <i class="ri-truck-line"></i> {{ __('Courier Charges') }}
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#banner" type="button">
                                    <i class="ri-layout-top-line"></i> {{ __('Banner Area') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#about" type="button">
                                    <i class="ri-information-line"></i> {{ __('About Us') }}
                                </button>
                            </li>

                        </ul>
                    </div>

                    <!-- Tab Contents -->
                    <div class="tab-content mt-4">

                        <!-- ================= BASIC INFO ================= -->
                        <div class="tab-pane fade show active" id="basic">

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Site Title</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="site_title" class="form-control"
                                           value="{{ get_settings('site_title') }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Phone</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="phone" class="form-control"
                                           value="{{ get_settings('phone') }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Email</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="email" name="email" class="form-control"
                                           value="{{ get_settings('email') }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Address</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="address" class="form-control"
                                           value="{{ get_settings('address') }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">WhatsApp Number</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="whatsapp_number" class="form-control"
                                           value="{{ get_settings('whatsapp_number') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Became Affiliate</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="became_affiliate" class="form-control"
                                           value="{{ get_settings('became_affiliate') }}">
                                </div>
                            </div>

                        </div>


                        <!-- ================= BRANDING ================= -->
                        <div class="tab-pane fade" id="branding">

                            <!-- Logo -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Logo</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" class="form-control" name="logo">
                                </div>
                            </div>

                            <!-- Footer Logo -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Footer Logo</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" class="form-control" name="footer_logo">
                                </div>
                            </div>

                            <!-- Favicon -->
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Favicon</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="file" class="form-control" name="favicon">
                                </div>
                            </div>

                        </div>


                        <!-- ================= SOCIAL MEDIA ================= -->
                        <div class="tab-pane fade" id="social">

                            @foreach (['facebook', 'twitter', 'youtube', 'instagram', 'telegram', 'linkedin'] as $social)
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-4 text-md-end">
                                        <label class="form-label text-capitalize">{{ ucfirst($social) }}</label>
                                    </div>
                                    <div class="col-md-8">
                                        <input type="text" name="{{ $social }}" class="form-control"
                                               value="{{ get_settings($social) }}">
                                    </div>
                                </div>
                            @endforeach

                        </div>


                        <!-- ================= MOBILE APP ================= -->
                        <div class="tab-pane fade" id="app">

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">App Section Title</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="app_section_title" class="form-control"
                                           value="{{ get_settings('app_section_title') }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">App Section Description</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="3"
                                              name="app_section_description">{{ get_settings('app_section_description') }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Play Store Link</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="app_play_store_link" class="form-control"
                                           value="{{ get_settings('app_play_store_link') }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">App Store Link</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="app_app_store_link" class="form-control"
                                           value="{{ get_settings('app_app_store_link') }}">
                                </div>
                            </div>

                        </div>


                        <!-- ================= FOOTER ================= -->
                        <div class="tab-pane fade" id="footer">

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Footer Description</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="4"
                                              name="footer_description">{{ get_settings('footer_description') }}</textarea>
                                </div>
                            </div>

                        </div>



                        <!-- ================= COURIER ================= -->
                        <div class="tab-pane fade" id="courier">

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Delivery In Dhaka</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="delivery_cost_in_dhaka" class="form-control"
                                           value="{{ get_settings('delivery_cost_in_dhaka') }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Delivery Outside Dhaka</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="delivery_cost_outside_dhaka" class="form-control"
                                           value="{{ get_settings('delivery_cost_outside_dhaka') }}">
                                </div>
                            </div>

                        </div>


                        <!-- ================= Banner Area ================= -->
                        <div class="tab-pane fade" id="banner">

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Badge Text</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="badge_text" class="form-control"
                                           value="{{ get_settings('badge_text') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Title</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="home_title" class="form-control"
                                           value="{{ get_settings('home_title') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Description</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="3"
                                              name="home_description">{{ get_settings('home_description') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Banner Image (583x362px)</label>
                                </div>
                                <div class="col-md-8">
                                    @if(get_settings('home_banner'))
                                        <a target="_blank" href="{{asset(get_settings('home_banner'))}}">
                                            <img src="{{asset(get_settings('home_banner'))}}" alt=""
                                                 style="width: 100px">
                                        </a>
                                    @endif
                                    <input type="file" name="home_banner" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Button Text</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="button_text" class="form-control"
                                           value="{{ get_settings('button_text') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Button Link</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="button_link" class="form-control"
                                           value="{{ get_settings('button_link') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Button Text 2</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="button_text2" class="form-control"
                                           value="{{ get_settings('button_text2') }}">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Button Link 2</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="button_link2" class="form-control"
                                           value="{{ get_settings('button_link2') }}">
                                </div>
                            </div>

                        </div>
                        <!-- =================About Us ================= -->
                        <div class="tab-pane fade" id="about">

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Top Title</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="about_top_title" class="form-control"
                                           value="{{ get_settings('about_top_title') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Top Text</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="3"
                                              name="about_top_text">{{ get_settings('about_top_text') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Top Banner (360x324px)</label>
                                </div>
                                <div class="col-md-8">
                                    @if(get_settings('about_top_banner'))
                                        <a target="_blank" href="{{asset(get_settings('about_top_banner'))}}">
                                            <img src="{{asset(get_settings('about_top_banner'))}}" alt=""
                                                 style="width: 100px">
                                        </a>
                                    @endif
                                    <input type="file" name="about_top_banner" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Feature Title</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="about_featured_title" class="form-control"
                                           value="{{ get_settings('about_featured_title') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Feature Text</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="about_featured_text" class="form-control"
                                           value="{{ get_settings('about_featured_text') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Feature Title 2</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="about_featured_title2" class="form-control"
                                           value="{{ get_settings('about_featured_title2') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Feature Text 2</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="about_featured_text2" class="form-control"
                                           value="{{ get_settings('about_featured_text2') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Bottom Title</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="about_bottom_title" class="form-control"
                                           value="{{ get_settings('about_bottom_title') }}">
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Bottom Text</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea class="form-control" rows="3"
                                              name="about_bottom_text">{{ get_settings('about_bottom_text') }}</textarea>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-4 text-md-end">
                                    <label class="form-label">Bottom Banner (360x324px)</label>
                                </div>
                                <div class="col-md-8">
                                    @if(get_settings('about_bottom_banner'))
                                        <a target="_blank" href="{{asset(get_settings('about_bottom_banner'))}}">
                                            <img src="{{asset(get_settings('about_bottom_banner'))}}" alt=""
                                                 style="width: 100px">
                                        </a>
                                    @endif
                                    <input type="file" name="about_bottom_banner" class="form-control">
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-2">
                        <button class="tc-primary-btn">
                            <i class="fa fa-save"></i>Update Settings
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>
@endsection
