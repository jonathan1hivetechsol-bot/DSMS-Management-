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

               <li class="menu-title">Admin Portal</li>

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
                                   <a class="sub-nav-link" href="{{ route('any', 'dashboards/analytics')}}">Analytics</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarStudents" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarStudents">
                         <span class="nav-icon">
                              <i class="ri-user-line"></i>
                         </span>
                         <span class="nav-text">Students</span>
                    </a>
                    <div class="collapse" id="sidebarStudents">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('students.index') }}">All Students</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('students.create') }}">Add Student</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarTeachers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTeachers">
                         <span class="nav-icon">
                              <i class="ri-user-star-line"></i>
                         </span>
                         <span class="nav-text">Teachers</span>
                    </a>
                    <div class="collapse" id="sidebarTeachers">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('teachers.index') }}">All Teachers</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('teachers.create') }}">Add Teacher</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarClasses" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarClasses">
                         <span class="nav-icon">
                              <i class="ri-building-line"></i>
                         </span>
                         <span class="nav-text">Classes</span>
                    </a>
                    <div class="collapse" id="sidebarClasses">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('classes.index') }}">All Classes</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('classes.create') }}">Add Class</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link" href="{{ route('attendance.index') }}">
                         <span class="nav-icon">
                              <i class="ri-file-list-3-line"></i>
                         </span>
                         <span class="nav-text">Student Attendance</span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarTeacherAttendance" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTeacherAttendance">
                         <span class="nav-icon">
                              <i class="ri-user-follow-line"></i>
                         </span>
                         <span class="nav-text">Teacher Attendance</span>
                    </a>
                    <div class="collapse" id="sidebarTeacherAttendance">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('teacher-attendance.index') }}">All Records</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('teacher-attendance.report') }}">Reports</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link" href="{{ route('invoices.index') }}">
                         <span class="nav-icon">
                              <i class="ri-wallet-3-line"></i>
                         </span>
                         <span class="nav-text">Invoices</span>
                    </a>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarPayroll" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPayroll">
                         <span class="nav-icon">
                              <i class="ri-bank-card-line"></i>
                         </span>
                         <span class="nav-text">Payroll</span>
                    </a>
                    <div class="collapse" id="sidebarPayroll">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('payroll.index') }}">All Payrolls</a>
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
                                   <a class="sub-nav-link" href="{{ route('loans.index') }}">Loans</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarSubjects" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarSubjects">
                         <span class="nav-icon">
                              <i class="ri-pages-line"></i>
                         </span>
                         <span class="nav-text">Subjects</span>
                    </a>
                    <div class="collapse" id="sidebarSubjects">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('subjects.index') }}">All Subjects</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('subjects.create') }}">Add Subject</a>
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
                                   <a class="sub-nav-link" href="{{ route('exam-schedules.index') }}">All Schedules</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('exam-schedules.create') }}">Schedule Exam</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarGrades" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarGrades">
                         <span class="nav-icon">
                              <i class="ri-bar-chart-2-line"></i>
                         </span>
                         <span class="nav-text">Grades</span>
                    </a>
                    <div class="collapse" id="sidebarGrades">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('grades.index') }}">All Grades</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('grades.create') }}">Add Grade</a>
                              </li>
                         </ul>
                    </div>
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
                                   <a class="sub-nav-link" href="{{ route('announcements.index') }}">All Announcements</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('announcements.create') }}">Post Announcement</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarStudentLeaves" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarStudentLeaves">
                         <span class="nav-icon">
                              <i class="ri-calendar-line"></i>
                         </span>
                         <span class="nav-text">Student Leaves</span>
                    </a>
                    <div class="collapse" id="sidebarStudentLeaves">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('student-leaves.dashboard') }}">Dashboard</a>
                              </li>
                         </ul>
                    </div>
               </li>

               <li class="nav-item">
                    <a class="nav-link menu-arrow" href="#sidebarWhatsApp" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarWhatsApp">
                         <span class="nav-icon">
                              <i class="ri-whatsapp-line"></i>
                         </span>
                         <span class="nav-text">WhatsApp Alerts</span>
                    </a>
                    <div class="collapse" id="sidebarWhatsApp">
                         <ul class="nav sub-navbar-nav">
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('whatsapp.index') }}">Dashboard</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('whatsapp.templates') }}">Templates</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('whatsapp.send') }}">Send Alert</a>
                              </li>
                              <li class="sub-nav-item">
                                   <hr class="my-1">
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('whatsapp.automation.dashboard') }}">🤖 Automation</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('whatsapp.automation.broadcast') }}">📢 Broadcast</a>
                              </li>
                              <li class="sub-nav-item">
                                   <a class="sub-nav-link" href="{{ route('whatsapp.automation.groups') }}">👥 Groups</a>
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
