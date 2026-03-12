<div class="main-nav">
     <!-- Sidebar Logo -->
     <div class="logo-box">
          <a href="{{ route('any', 'dashboards/analytics')}}" class="logo-dark">
               <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
               <img src="/images/logo-dark.png" class="logo-lg" alt="logo dark">
          </a>

          <a href="{{ route('any', 'dashboards/analytics')}}" class="logo-light">
               <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
               <img src="/images/logo-light.png" class="logo-lg" alt="logo light">
          </a>
     </div>

     <!-- Menu Toggle Button (sm-hover) -->
     <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
          <i class="ri-menu-2-line fs-24 button-sm-hover-icon"></i>
     </button>

     <div class="scrollbar" data-simplebar>

          <ul class="navbar-nav" id="navbar-nav">

               <li class="menu-title">Student Portal</li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                         <span class="nav-icon">
                              <i class="ri-dashboard-2-line"></i>
                         </span>
                         <span class="nav-text">Dashboard</span>
                    </a>
                    <div class="collapse" id="sidebarDashboards">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('any', 'dashboards/analytics')}}">Overview</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarGrades" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarGrades">
                         <span class="nav-icon">
                              <i class="ri-bar-chart-2-line"></i>
                         </span>
                         <span class="nav-text">My Grades</span>
                    </a>
                    <div class="collapse" id="sidebarGrades">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('grades.index') }}">View Grades</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link" href="{{ route('attendance.index') }}">
                         <span class="nav-icon">
                              <i class="ri-file-list-3-line"></i>
                         </span>
                         <span class="nav-text">My Attendance</span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarAnnouncements" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAnnouncements">
                         <span class="nav-icon">
                              <i class="ri-announcement-line"></i>
                         </span>
                         <span class="nav-text">Announcements</span>
                    </a>
                    <div class="collapse" id="sidebarAnnouncements">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('announcements.index') }}">View Announcements</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarStudentLeaves" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarStudentLeaves">
                         <span class="nav-icon">
                              <i class="ri-calendar-line"></i>
                         </span>
                         <span class="nav-text">Leaves</span>
                    </a>
                    <div class="collapse" id="sidebarStudentLeaves">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('student-leaves.index') }}">My Leaves</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('student-leaves.create') }}">Request Leave</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarExams" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarExams">
                         <span class="nav-icon">
                              <i class="ri-calendar-event-line"></i>
                         </span>
                         <span class="nav-text">Exams</span>
                    </a>
                    <div class="collapse" id="sidebarExams">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('exam-schedules.index') }}">Exam Schedule</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarLibrary" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLibrary">
                         <span class="nav-icon">
                              <i class="ri-book-open-line"></i>
                         </span>
                         <span class="nav-text">Library</span>
                    </a>
                    <div class="collapse" id="sidebarLibrary">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('books.index') }}">Books</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('loans.index') }}">My Loans</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarMessages" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMessages">
                         <span class="nav-icon">
                              <i class="ri-message-line"></i>
                         </span>
                         <span class="nav-text">Messages</span>
                    </a>
                    <div class="collapse" id="sidebarMessages">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('messages.index') }}">My Messages</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarInvoices" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarInvoices">
                         <span class="nav-icon">
                              <i class="ri-wallet-3-line"></i>
                         </span>
                         <span class="nav-text">Fees</span>
                    </a>
                    <div class="collapse" id="sidebarInvoices">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('invoices.index') }}">View Invoices</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="menu-title">User</li>
               <li class="nav-item">
                    <a class="nav-link" href="{{ route('second', ['dashboards', 'analytics']) }}">
                         <span class="nav-icon">
                              <i class="ri-door-open-line"></i>
                         </span>
                         <span class="nav-text">Switch Portal</span>
                    </a>
               </li>

          </ul>
     </div>
</div>
