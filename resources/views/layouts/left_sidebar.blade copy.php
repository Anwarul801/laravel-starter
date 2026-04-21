 <div class="vertical-menu">
     {{--
 @Author: Anwarul
 @Date: 2025-11-17 17:12:23
 @LastEditors: Anwarul
 @LastEditTime: 2026-03-03 15:05:30
 @Description: Innova IT
 --}}
     <div data-simplebar class="h-100">
         @auth()
             <!--- Sidemenu -->
             <div id="sidebar-menu">
                 <!-- Left Menu Start -->
                 <ul class="metismenu list-unstyled" id="side-menu">
                     <li>
                         <a href="{{ route('dashboard') }}" class="waves-effect">
                             <i class="ri-dashboard-line"></i>
                             <span>Dashboard</span>
                         </a>
                     </li>
                     @canany(['users.index', 'roles.index', 'permissions.index'])
                         <li>
                             <a href="javascript: void(0);" class="waves-effect">
                                 <i class="ri-account-circle-line"></i>
                                 <span>Users</span>
                                 <i class="float-right has-arrow"></i>
                             </a>
                             <ul class="sub-menu ml-3" aria-expanded="false">
                                 @can(['admin.index'])
                                     <li><a class="nav-link" href="{{ route('admin.index') }}"><i class=" ri-user-add-line"></i>
                                             Manage Admin</a></li>
                                 @endcan
                                 @can(['users.index'])
                                     <li><a class="nav-link" href="{{ route('users.index') }}"><i class=" ri-user-add-line"></i>
                                             Manage Users</a></li>
                                 @endcan
                                 @can(['permissions.index'])
                                     <li><a class="nav-link" href="{{ route('permissions.index') }}"> <i
                                                 class=" ri-lock-2-line"></i>
                                             Manage permission</a></li>
                                 @endcan
                                 @can(['roles.index'])
                                     <li><a class="nav-link" href="{{ route('roles.index') }}"><i class="ri-share-line"></i> Manage
                                             Role</a></li>
                                 @endcan
                             </ul>
                         </li>
                     @endcanany
                     @can('course.index')
                         <li>
                             <a href="{{ route('course.index') }}">
                                 <i class="fas fa-list"></i> Course List</a>
                         </li>
                     @endcan

                     @can('course_categories.index')
                         <li>
                             <a href="{{ route('course_categories.index') }}">
                                 <i class="fas fa-list"></i> Course Categories</a>
                         </li>
                     @endcan
                     @can('course_comment.index')
                         <li>
                             <a href="{{ route('course_comment.index') }}">
                                 <i class="fas fa-list"></i> Course Comments</a>
                         </li>
                     @endcan
                     @can('student_review.index')
                         <li>
                             <a href="{{ route('student_review.index') }}">
                                 <i class="fas fa-star"></i> Student Review</a>
                         </li>
                     @endcan
                     @can('enrolled_student.index')
                         <li>
                             <a href="{{ route('enrolled_student.index') }}">
                                 <i class="fas fa-list"></i> Enrolled Student</a>
                         </li>
                     @endcan
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
                     @can(['teacher.index'])
                         <li>
                             <a href="{{ route('teacher.index') }}" class="waves-effect">
                                 <i class="ri-user-3-line"></i>
                                 <span>Teachers</span>
                             </a>
                         </li>
                     @endcan
                     @can('quiz_question.index')
                         <li>
                             <a href="{{ route('quiz_question.index') }}">
                                 <i class="fas fa-list"></i> Quiz Question List</a>
                         </li>
                     @endcan
                     @can(['setting.index'])
                         <li>
                             <a href="{{ route('setting.index') }}" class=" waves-effect">
                                 <i class="ri-settings-2-line"></i>
                                 <span>Settings</span>
                             </a>
                         </li>
                     @endcan


                     @can(['slider.index'])
                         <li>
                             <a href="{{ route('slider.index') }}" class=" waves-effect">
                                 <i class=" ri-window-2-line"></i>
                                 <span>Sliders</span>
                             </a>
                         </li>
                     @endcan
                     @can(['faq.index'])
                         <li>
                             <a href="{{ route('faq.index') }}" class=" waves-effect">
                                 <i class="ri-questionnaire-fill"></i>
                                 <span>Faqs</span>
                             </a>
                         </li>
                     @endcan
                     @can(['important_link.index'])
                         <li>
                             <a href="{{ route('important_link.index') }}" class=" waves-effect">
                                 <i class=" ri-external-link-fill"></i>
                                 <span>Important Link</span>
                             </a>
                         </li>
                     @endcan
                     @can(['page.index'])
                         <li>
                             <a href="{{ route('page.index') }}" class=" waves-effect">
                                 <i class=" ri-pages-line"></i>
                                 <span>Pages</span>
                             </a>
                         </li>
                     @endcan
                     @canany(['blog.index', 'blog_category.index'])
                         <li>
                             <a href="javascript: void(0);" class="waves-effect">
                                 <i class="ri-book-line"></i>
                                 <span>Blog</span>
                                 <i class="float-right has-arrow"></i>
                             </a>
                             <ul class="sub-menu ml-3" aria-expanded="false">
                                 @can('blog_category.index')
                                     <li>
                                         <a class="nav-link" href="{{ route('blog_category.index') }}">
                                             <i class="ri-book-mark-line"></i> Blog Categories
                                         </a>
                                     </li>
                                 @endcan
                                 @can('blog.index')
                                     <li>
                                         <a class="nav-link" href="{{ route('blog.index') }}">
                                             <i class="ri-file-list-line"></i> Blog
                                         </a>
                                     </li>
                                 @endcan

                             </ul>
                         </li>
                     @endcanany
                     @can(['job_circular.index'])
                         <li>
                             <a href="{{ route('job_circular.index') }}" class="waves-effect">
                                 <i class="ri-briefcase-line"></i>
                                 <span>Job Circular</span>
                             </a>
                         </li>
                     @endcan

                 @can(['contact_message.index'])
                         <li>
                             <a href="{{ route('contact_message.index') }}" class="waves-effect">
                                 <i class="ri-mail-line"></i>
                                 <span>Contact Messages</span>
                             </a>
                         </li>
                     @endcan

                 </ul>
             </div>
             <!-- Sidebar -->
         @endauth
     </div>
 </div>
