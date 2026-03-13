<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8" x-data="attendanceHandler()">
        <h2 class="text-3xl font-extrabold text-center mb-8 text-[#003049] tracking-tight">Sistem Absensi</h2>

        @if (session()->has('error'))
            <div class="p-4 mb-5 text-red-700 bg-red-50 border border-red-200 rounded-lg font-medium text-sm">{{ session('error') }}</div>
        @endif
        @if (session()->has('success'))
            <div class="p-4 mb-5 text-green-700 bg-green-50 border border-green-200 rounded-lg font-medium text-sm">{{ session('success') }}</div>
        @endif

        <div class="space-y-6">
            <!-- NIP is now stored in session securely -->

            <!-- Camera Preview -->
            <div class="w-full rounded-xl overflow-hidden relative bg-gray-900 shadow-inner group flex flex-col items-center">
                <video id="video-preview" class="w-full aspect-square sm:aspect-video object-cover" autoplay playsinline></video>
                <canvas id="photo-canvas" style="display: none;"></canvas>
                
                <button type="button" @click="takeSnapshot" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 px-5 py-2.5 bg-black/40 backdrop-blur-md border border-white/30 text-white text-sm font-semibold rounded-full hover:bg-black/60 transition shadow-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Ambil Foto
                </button>
            </div>

            <!-- GPS Status Indicator -->
            <div class="flex items-center justify-center p-3.5 rounded-xl border" :class="gpsLocked ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'">
                <span class="text-sm font-semibold" x-text="gpsMessage"></span>
            </div>

            <!-- Submit Action -->
            <button type="button" 
                    wire:click="submit" 
                    x-bind:disabled="!readyToSubmit" 
                    class="w-full py-4 bg-[#D62828] hover:bg-[#b02121] text-white rounded-xl font-bold text-lg disabled:opacity-60 disabled:cursor-not-allowed transition-all shadow-md focus:ring-4 focus:ring-red-200">
                Submit Absensi
            </button>
        </div>
    </div>
</div>

<!-- JavaScript Integration Explanation -->
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceHandler', () => ({
        gpsLocked: false,
        gpsMessage: '📍 Mencari Lokasi GPS...',
        photoCaptured: false,

        get readyToSubmit() {
            return this.gpsLocked && this.photoCaptured;
        },

        init() {
            this.initCamera();
            this.initGPS();
        },

        // 1. Camera Integration (HTML5 MediaDevices)
        // Requests user permission to access camera and pipes the stream directly to our <video> tag
        initCamera() {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } })
                    .then(stream => {
                        document.getElementById('video-preview').srcObject = stream;
                    })
                    .catch(err => alert("Camera error: " + err));
            }
        },

        // 2. GPS Integration (HTML5 Geolocation API)
        // Requests high-accuracy coordinates. Updates Livewire variables instantly once found.
        initGPS() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        // Push variables back to Livewire backend
                        @this.set('latitude', position.coords.latitude);
                        @this.set('longitude', position.coords.longitude);
                        this.gpsLocked = true;
                        this.gpsMessage = '✅ Lokasi GPS Telah Dikunci';
                    },
                    (error) => {
                        this.gpsMessage = '❌ Gagal Mendapatkan Lokasi. Izinkan akses GPS untuk absen.';
                    },
                    { enableHighAccuracy: true }
                );
            } else {
                this.gpsMessage = 'Browser Anda tidak mendukung Geolocation.';
            }
        },

        takeSnapshot() {
            const video = document.getElementById('video-preview');
            const canvas = document.getElementById('photo-canvas');
            const context = canvas.getContext('2d');
            
            // Match canvas size to video stream
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Convert to base64 to send to backend and tell alpine the photo is ready
            const dataUrl = canvas.toDataURL('image/png');
            @this.set('photo', dataUrl);
            this.photoCaptured = true;
            alert("Foto berhasil ditangkap!");
        }
    }));
});
</script>
