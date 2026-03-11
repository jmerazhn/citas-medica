<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\AppointmentSlotService;
use Carbon\Carbon;
use Livewire\Component;

class AppointmentForm extends Component
{
    public ?int $doctorId = null;
    public ?string $date = null;
    public array $availableSlots = [];

    public function mount(?int $initialDoctorId = null, ?string $initialDate = null): void
    {
        $this->doctorId = $initialDoctorId;
        $this->date = $initialDate;

        if ($this->doctorId && $this->date) {
            $this->recalculateSlots();
        }
    }

    public function updatedDoctorId(): void
    {
        $this->recalculateSlots();
    }

    public function updatedDate(): void
    {
        $this->recalculateSlots();
    }

    private function recalculateSlots(): void
    {
        $this->availableSlots = [];

        if (!$this->doctorId || !$this->date) {
            return;
        }

        $doctor = User::find($this->doctorId);
        if (!$doctor) {
            return;
        }

        try {
            $date = Carbon::parse($this->date);
            $service = new AppointmentSlotService();
            $this->availableSlots = $service->getAvailableSlots($doctor, $date);
        } catch (\Exception $e) {
            $this->availableSlots = [];
        }
    }

    public function render()
    {
        $doctors = User::role('Doctor')->orderBy('name')->get();

        return view('livewire.admin.appointment-form', compact('doctors'));
    }
}
