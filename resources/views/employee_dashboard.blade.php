<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal - DJLN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .timeline-scroll::-webkit-scrollbar { width: 4px; }
        .timeline-scroll::-webkit-scrollbar-track { background: #1e293b; }
        .timeline-scroll::-webkit-scrollbar-thumb { background: #475569; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-100 h-screen font-sans overflow-hidden flex items-center justify-center p-6">
    
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl h-[600px] flex overflow-hidden">
        
        <!-- LEFT SIDE: TIMELINE -->
        <div class="w-1/3 bg-[#0F172A] p-8 text-white flex flex-col relative">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Attendance History</h3>
            
            <div class="flex-1 overflow-y-auto timeline-scroll space-y-8 pr-2 relative">
                <div class="absolute left-[7px] top-2 bottom-0 w-[2px] bg-gray-700"></div>

                @forelse(Auth::guard('employee')->user()->attendances()->latest()->take(5)->get() as $log)
                <div class="relative pl-8">
                    <!-- Check status content safely -->
                    <div class="absolute left-0 top-1.5 w-4 h-4 rounded-full border-2 border-[#0F172A] 
                        {{ stripos($log->Status, 'Out') !== false ? 'bg-gray-500' : 'bg-green-500' }}"></div>
                    
                    <p class="text-sm font-bold text-gray-200">{{ \Carbon\Carbon::parse($log->Date)->format('M d, Y') }}</p>
                    <div class="text-xs text-gray-400 mt-1">
                        <span class="block">In: {{ $log->CheckInTime ? \Carbon\Carbon::parse($log->CheckInTime)->format('h:i A') : '--:--' }}</span>
                        <span class="block">Out: {{ $log->CheckOutTime ? \Carbon\Carbon::parse($log->CheckOutTime)->format('h:i A') : '--:--' }}</span>
                    </div>
                </div>
                @empty
                <div class="pl-8 text-sm text-gray-500">No history yet.</div>
                @endforelse
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-800 flex justify-between items-center text-xs text-gray-500">
                <span>DJLN Systems</span>
                <span>v1.0</span>
            </div>
        </div>


        <!-- RIGHT SIDE: PROFILE & STATS -->
        <div class="w-2/3 bg-white p-10 flex flex-col relative">
            
            <!-- Header -->
            <div class="flex justify-between items-start mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold text-blue-900 tracking-tight">{{ now()->format('l') }}</h1>
                    <p class="text-lg text-gray-500 font-medium">{{ now()->format('F d, Y') }}</p>
                </div>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="bg-red-50 text-red-500 p-3 rounded-full hover:bg-red-100 transition shadow-sm">
                        <i data-lucide="log-out" class="h-5 w-5"></i>
                    </button>
                </form>
            </div>

            <!-- Profile -->
            <div class="flex flex-col items-center flex-1 justify-center">
                
                <div class="relative mb-6 group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full opacity-75 group-hover:opacity-100 transition duration-200 blur"></div>
                    <div class="relative w-32 h-32 rounded-full border-4 border-white shadow-xl overflow-hidden bg-gray-100">
                        @if(Auth::guard('employee')->user()->Photo)
                            <img src="{{ asset('storage/' . Auth::guard('employee')->user()->Photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-blue-300 text-4xl font-bold bg-slate-50">
                                {{ substr(Auth::guard('employee')->user()->FirstName, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-800 mb-1">
                    {{ Auth::guard('employee')->user()->FirstName }} {{ Auth::guard('employee')->user()->LastName }}
                </h2>
                <p class="text-sm font-semibold text-blue-500 bg-blue-50 px-3 py-1 rounded-full mb-8">
                    {{ Auth::guard('employee')->user()->department->DepartmentName ?? 'Employee' }}
                </p>

                <!-- STATUS BOX (ROBUST LOGIC) -->
                <div class="w-full bg-gray-50 rounded-2xl p-6 border border-gray-100 text-center shadow-inner">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Today's Status</p>
                    
                    @php
                        $status = Auth::guard('employee')->user()->Status;
                        // DEBUG: Uncomment below line to see raw status on screen if needed
                        // echo "RAW STATUS: " . $status; 
                    @endphp

                    @if(stripos($status, 'Time In') !== false)
                        <div class="text-4xl font-black text-blue-900 mb-1">
                            {{ str_replace('Time In: ', '', $status) }}
                        </div>
                        <p class="text-sm font-bold text-green-600 uppercase tracking-wide flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="h-4 w-4"></i> Clocked In
                        </p>
                    @elseif(stripos($status, 'Time Out') !== false)
                        <div class="text-4xl font-black text-gray-800 mb-1">
                            {{ str_replace('Time Out: ', '', $status) }}
                        </div>
                        <p class="text-sm font-bold text-orange-500 uppercase tracking-wide flex items-center justify-center gap-2">
                            <i data-lucide="clock" class="h-4 w-4"></i> Clocked Out
                        </p>
                    @else
                        <!-- Fallback -->
                        <div class="text-3xl font-bold text-gray-400 mb-1">-- : --</div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">
                            Not Timed In Yet
                            <!-- Only show this debug hint if needed -->
                            <!-- (Current DB Status: {{ $status }}) -->
                        </p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
      lucide.createIcons();
    </script>
</body>
</html>