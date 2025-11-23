<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DJLN Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-900 antialiased">
    
    <!-- If we are on the Login Page, just show content (No Sidebar) -->
    @if(Request::is('/'))
        @yield('content')
    @else
        <!-- For all Dashboard Pages, Show Sidebar + Content -->
        <div class="flex h-screen overflow-hidden">
            <!-- Include the Master Sidebar -->
            @include('partials.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col ml-64 overflow-y-auto">
                @yield('content')
            </div>
        </div>
    @endif
    
    <script>
      lucide.createIcons();
    </script>
</body>
</html>