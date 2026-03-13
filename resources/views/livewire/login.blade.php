<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-8 transform transition-all">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-[#003049] tracking-tight mb-2">HRIS PT. SSS</h1>
            <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Portal Absensi</p>
        </div>

        @if (session()->has('error'))
            <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="authenticate" class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">ID Karyawan / NIP</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                    </div>
                    <input type="text" wire:model.defer="nip" class="w-full pl-11 pr-4 py-4 bg-gray-50/50 border border-gray-200 focus:bg-white focus:ring-2 focus:ring-[#003049] focus:border-[#003049] focus:outline-none rounded-2xl transition-all shadow-sm text-lg font-medium" placeholder="Masukkan NIP Anda" autocomplete="off" required>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-[#D62828] hover:bg-[#b02121] text-white rounded-2xl font-bold text-lg transition-all shadow-lg hover:shadow-xl focus:ring-4 focus:ring-red-200 flex justify-center items-center gap-2 group">
                Lanjutkan Absen
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>
    </div>
</div>
