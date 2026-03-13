<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance as AttendanceModel;
use App\Models\Schedule;
use Carbon\Carbon;

class Success extends Component
{
    public $attendance;
    public $status = 'Hadir Tepat Waktu';
    public $isLate = false;

    public function mount()
    {
        $attendanceId = session('last_attendance_id');

        if (!$attendanceId) {
            return redirect()->route('login');
        }

        $this->attendance = AttendanceModel::with('employee')->find($attendanceId);

        if (!$this->attendance) {
            return redirect()->route('login');
        }

        // Determine late status based on check-in time and schedule
        $checkInTime = Carbon::parse($this->attendance->check_in_time);
        $dayName = $checkInTime->locale('id')->isoFormat('dddd'); // e.g. Senin, Selasa

        $schedule = Schedule::where('day', strtolower($dayName))->first();

        // Check if there is a schedule config to compare
        if ($schedule) {
            $shiftStartTime = Carbon::parse($checkInTime->format('Y-m-d') . ' ' . $schedule->start_time);
            if ($checkInTime->greaterThan($shiftStartTime)) {
                $this->isLate = true;
                $this->status = 'Terlambat';
            }
        }
    }

    public function render()
    {
        return view('livewire.success');
    }
}
