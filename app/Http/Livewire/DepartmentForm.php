<?php

namespace App\Http\Livewire;

use App\Models\Departent;
use Livewire\Component;

class DepartmentForm extends Component
{

    public $name = 'Accounting';
    public $success = false;

    public function submit()
    {
        Departent::create([
            'name' => $this->name
        ]);

        $this->success = true;
    }

    public function mount($departmentId = null)
    {
        if($departmentId){
            $this->name = Departent::findOrFail($departmentId)->name;
        }
    }

    public function render()
    {
        return view('livewire.department-form');
    }
}
