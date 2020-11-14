<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddUser extends Component
{
    use WithFileUploads;

    public $name = "Kevin McKee";
    public $email = "kevin@lc.com";
    public $department = 'information_technology';
    public $title = "Instructor";
    public $photo;
    public $status = 1;
    public $role = 'admin';
    public $application;

    public function submit()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'department' => 'required|string',
            'title' => 'required|string',
            'status' => 'required|boolean',
            'role' => 'required|string',
            'photo' => 'image|max:1024', // 1MB Max
            'application' => 'file|mimes:pdf|max:1024', // 1MB Max
        ]);

        $filename = $this->photo->store('photos', 's3-public');

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'department' => $this->department,
            'title' => $this->title,
            'status' => $this->status,
            'role' => $this->role,
            'photo' => $filename,
            'password' => bcrypt(Str::random(16))
        ]);

        $filename = pathinfo($this->application->getClientOriginalName(), PATHINFO_FILENAME)
            . '_' . now()->timestamp . '.' . $this->application->getClientOriginalExtension();

        $this->application->storeAs('/documents/' . $user->id . '/', $filename, 's3');

        $user->documents()->create([
            'type' => 'application',
            'filename' => $filename,
            'extention' => $this->application->getClientOriginalExtension(),
            'size' => $this->application->getSize()
        ]);

        session()->flash('success', 'We Did It');

    }

    public function render()
    {
        return view('livewire.add-user');
    }
}
