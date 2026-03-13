<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\User;
use App\Models\Attendance as AttendanceModel;
use Illuminate\Support\Facades\Storage;

class Attendance extends Component
{
    public $photo; // base64 string
    public $latitude;
    public $longitude;

    public function mount()
    {
        if (!session()->has('attendance_nip')) {
            return redirect()->route('login');
        }
    }
    public function submit()
    {
        $this->validate([
            'photo' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $nip = session('attendance_nip');
        $employee = User::where('nip', $nip)->first();

        if (!$employee) {
            session()->flash('error', 'Sesi tidak valid, NIP tidak ditemukan.');
            return redirect()->route('login');
        }

        // Decode Base64 captured photo
        $photoData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->photo));
        $photoName = 'attendance/' . time() . '_' . $nip . '.png';
        Storage::disk('public')->put($photoName, $photoData);

        // Check if employee already checked in today
        $todayAttendance = AttendanceModel::where('employee_id', $employee->id)
            ->whereDate('check_in_time', today())
            ->first();

        if ($todayAttendance) {
            // Do Check-Out
            $todayAttendance->update(['check_out_time' => now()]);
            $attendanceRecord = $todayAttendance;
            session()->flash('success', 'Berhasil Check-Out!');
        } else {
            // Do Check-In
            $attendanceRecord = AttendanceModel::create([
                'employee_id' => $employee->id,
                'check_in_time' => now(),
                'attendance_photo' => $photoName,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ]);
            session()->flash('success', 'Berhasil Check-In!');
        }
        
        // Remove NIP session, add success record session
        session()->forget('attendance_nip');
        session(['last_attendance_id' => $attendanceRecord->id]);

        return redirect()->route('success');
    }

    public function render()
    {
        return view('livewire.attendance'); 
    }
}
