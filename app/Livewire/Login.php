<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class Login extends Component
{
    public $nip;

    public function mount()
    {
        // If already logged in to attendance, redirect
        if (session()->has('attendance_nip')) {
            return redirect()->route('attendance');
        }
    }

    public function authenticate()
    {
        $this->validate([
            'nip' => 'required|string',
        ]);

        $employee = User::where('nip', $this->nip)->first();

        if (!$employee) {
            session()->flash('error', 'NIP tidak ditemukan dalam sistem!');
            return;
        }

        // Store NIP in session
        session(['attendance_nip' => $this->nip]);

        return redirect()->route('attendance');
    }

    public function render()
    {
        return view('livewire.login');
    }
}
