@extends('layout')

@section('content')
<div class="p-8 h-full">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 min-h-[600px] flex flex-col">
        
        <!-- Page Title -->
        <div class="flex items-center mb-8">
            <div class="w-1.5 h-6 bg-orange-400 mr-3 rounded-full"></div>
            <h1 class="text-xl font-bold text-blue-900">Activity Logs</h1>
        </div>

        <!-- Logic: Check if there are logs -->
        @if($activities->isEmpty())
            
            <!-- EMPTY STATE -->
            <div class="flex-1 flex flex-col items-center justify-center text-center opacity-80">
                <div class="w-64 h-64 mb-6 relative">
                     <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-gray-200 fill-current">
                        <circle cx="100" cy="100" r="80" class="text-gray-100"/>
                        <path d="M140 140 L180 180" stroke="currentColor" stroke-width="12" stroke-linecap="round"/>
                        <circle cx="90" cy="90" r="35" stroke="currentColor" stroke-width="12" fill="none"/>
                        <path d="M60 140 Q100 160 140 140" stroke="currentColor" stroke-width="4" fill="none" class="text-orange-200"/>
                     </svg>
                     <div class="absolute inset-0 flex items-center justify-center">
                        <i data-lucide="clipboard-x" class="h-16 w-16 text-gray-400"></i>
                     </div>
                </div>

                <h2 class="text-2xl font-bold text-blue-900 mb-2">No activity logs found</h2>
                <p class="text-gray-500 max-w-xs">It seems there is no activity yet. Once you register employees or they log in, the timeline will appear here.</p>
                
                <a href="{{ route('register') }}" class="mt-8 px-6 py-3 bg-orange-100 text-orange-600 font-bold rounded-full hover:bg-orange-200 transition">
                    Register First Employee
                </a>
            </div>

        @else

            <!-- POPULATED STATE (Timeline) -->
            <div class="max-w-3xl">
                <ul class="space-y-6 relative border-l-2 border-gray-100 ml-3">
                    @foreach($activities as $log)
                    <li class="mb-10 ml-6">
                        <!-- The Green Dot -->
                        <span class="absolute flex items-center justify-center w-6 h-6 bg-green-100 rounded-full -left-3.5 ring-4 ring-white">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        </span>
                        
                        <!-- Content Card -->
                        <div class="p-4 bg-white border border-gray-100 rounded-lg shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-bold text-gray-800">{{ $log->description }}</span>
                            </div>
                            <!-- UPDATED: Uses 'diffForHumans' to show "25 mins ago" -->
                            <time class="block mb-2 text-xs font-normal text-gray-400">
                                {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                            </time>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

        @endif

    </div>
</div>
@endsection