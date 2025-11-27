<div class="w-64 bg-white shadow-xl flex flex-col z-10 fixed h-full border-r border-gray-100">
    <!-- Sidebar Header -->
    <div class="h-32 flex flex-col items-center justify-center border-b border-gray-100 bg-white">
       <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200 mb-2 overflow-hidden relative">
          <div class="absolute inset-0 flex items-center justify-center">
              <span class="text-[8px] font-bold text-blue-900 text-center">LOGO</span>
          </div>
       </div>
       <h2 class="text-blue-900 font-bold text-sm tracking-wide">ADMIN</h2>
       <h2 class="text-blue-900 font-bold text-lg leading-none">DASHBOARD</h2>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6 px-4">
        <div class="mb-4 pl-2"><span class="text-[10px] font-bold text-orange-400 uppercase tracking-widest">Main Menu</span></div>
        
        <ul class="space-y-2">
            <!-- Employee Link -->
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ Request::routeIs('dashboard') ? 'bg-orange-100 text-blue-900 font-bold rounded-lg relative overflow-hidden' : 'text-gray-500 hover:text-orange-500 transition' }}">
                    @if(Request::routeIs('dashboard')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orange-400"></div> @endif
                    <i data-lucide="users" class="h-5 w-5 mr-3 {{ Request::routeIs('dashboard') ? 'ml-2' : '' }}"></i>
                    <span>Employee</span>
                </a>
            </li>
            
            <!-- Register Link -->
            <li>
                <a href="{{ route('register') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ Request::routeIs('register') ? 'bg-orange-100 text-blue-900 font-bold rounded-lg relative overflow-hidden' : 'text-gray-500 hover:text-orange-500 transition' }}">
                    @if(Request::routeIs('register')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orange-400"></div> @endif
                    <i data-lucide="clipboard-list" class="h-5 w-5 mr-3 {{ Request::routeIs('register') ? 'ml-2' : '' }}"></i> 
                    <span>Register</span>
                </a>
            </li>

            <!-- Logs Link -->
            <li>
                <a href="{{ route('logs') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ Request::routeIs('logs') ? 'bg-orange-100 text-blue-900 font-bold rounded-lg relative overflow-hidden' : 'text-gray-500 hover:text-orange-500 transition' }}">
                    @if(Request::routeIs('logs')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orange-400"></div> @endif
                    <i data-lucide="calendar" class="h-5 w-5 mr-3 {{ Request::routeIs('logs') ? 'ml-2' : '' }}"></i> 
                    <span>Logs</span>
                </a>
            </li>

            <!-- Report Link (THIS WAS THE ISSUE) -->
            <li>
                <a href="{{ route('report') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ Request::routeIs('report') ? 'bg-orange-100 text-blue-900 font-bold rounded-lg relative overflow-hidden' : 'text-gray-500 hover:text-orange-500 transition' }}">
                    @if(Request::routeIs('report')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orange-400"></div> @endif
                    <i data-lucide="mail" class="h-5 w-5 mr-3 {{ Request::routeIs('report') ? 'ml-2' : '' }}"></i> 
                    <span>Report</span>
                </a>
            </li>

            <!-- Activity Logs Link -->
            <li>
                <a href="{{ route('activity.logs') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ Request::routeIs('activity.logs') ? 'bg-orange-100 text-blue-900 font-bold rounded-lg relative overflow-hidden' : 'text-gray-500 hover:text-orange-500 transition' }}">
                    @if(Request::routeIs('activity.logs')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orange-400"></div> @endif
                    <i data-lucide="clock" class="h-5 w-5 mr-3 {{ Request::routeIs('activity.logs') ? 'ml-2' : '' }}"></i> 
                    <span>Activity Logs</span>
                </a>
            </li>

            <!-- Employee Attendance Link -->
            <li>
                <a href="{{ route('attendance.analytics') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ Request::routeIs('attendance.analytics') ? 'bg-orange-100 text-blue-900 font-bold rounded-lg relative overflow-hidden' : 'text-gray-500 hover:text-orange-500 transition' }}">
                    @if(Request::routeIs('attendance.analytics')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orange-400"></div> @endif
                    <i data-lucide="pie-chart" class="h-5 w-5 mr-3 {{ Request::routeIs('attendance.analytics') ? 'ml-2' : '' }}"></i> 
                    <span>Employee Attendance</span>
                </a>
            </li>
        </ul>

        <div class="mt-8 mb-4 pl-2"><span class="text-[10px] font-bold text-orange-400 uppercase tracking-widest">Settings</span></div>
        <ul class="space-y-2">
             <li>
                <a href="{{ route('settings') }}" class="flex items-center px-4 py-3 text-sm font-medium {{ Request::routeIs('settings') ? 'bg-orange-100 text-blue-900 font-bold rounded-lg relative overflow-hidden' : 'text-gray-500 hover:text-orange-500 transition' }}">
                    @if(Request::routeIs('settings')) <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-orange-400"></div> @endif
                    <i data-lucide="settings" class="h-5 w-5 mr-3 {{ Request::routeIs('settings') ? 'ml-2' : '' }}"></i> 
                    <span>Settings</span>
                </a>
             </li>
             
             <!-- Logout Button -->
             <li>
                <form action="{{ route('logout') }}" method="POST"> 
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-500 hover:text-red-500 transition">
                        <i data-lucide="log-out" class="h-5 w-5 mr-3"></i> 
                        <span>Logout</span>
                    </button>
                </form>
             </li>
        </ul>
    </nav>
</div>