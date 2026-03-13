<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-8 transform transition-all text-center">
        
        <!-- Animated Success Badge -->
        <div class="mx-auto w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </div>

        <h1 class="text-2xl font-extrabold text-[#003049] tracking-tight mb-2">Absensi Berhasil!</h1>
        <p class="text-sm font-medium text-gray-500 mb-6">Terima kasih, data kehadiran Anda telah tercatat.</p>

        <!-- Profile & Photo Data -->
        @if($attendance)
        <div class="bg-gray-50 rounded-2xl p-5 mb-6 text-left shadow-inner border border-gray-100">
            <div class="flex items-center gap-4 mb-4">
                <img src="{{ Storage::url($attendance->attendance_photo) }}" alt="Foto Selfie" class="w-16 h-16 rounded-full object-cover border-2 border-[#003049] shadow-sm">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">{{ $attendance->employee->name }}</h3>
                    <p class="text-xs font-semibold text-gray-500">{{ $attendance->employee->nip }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-3 rounded-xl border border-gray-100 flex flex-col items-center justify-center shadow-sm">
                    <span class="text-xs font-bold text-gray-400 uppercase mb-1">Waktu</span>
                    <span class="text-sm font-semibold text-[#003049]">
                        {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i:s') }}
                    </span>
                </div>
                
                <div class="bg-white p-3 rounded-xl border border-gray-100 flex flex-col items-center justify-center shadow-sm">
                    <span class="text-xs font-bold text-gray-400 uppercase mb-1">Status</span>
                    <span class="text-sm font-bold {{ $isLate ? 'text-orange-600' : 'text-green-600' }}">
                        {{ $status }}
                    </span>
                </div>
            </div>
        </div>
        @endif

        <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full py-4 bg-[#003049] hover:bg-[#001f33] text-white rounded-2xl font-bold text-lg transition-all shadow-lg hover:shadow-xl focus:ring-4 focus:ring-blue-200">
            Kembali ke Beranda
        </a>
    </div>
</div>
