<?php

namespace App\Livewire;

use App\Models\Brand;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Home page - Oussama')]
class HomePage extends Component

{
    public function render()
    {
        $brands=Brand::where('is_active');
        return view('livewire.home-page',['brands'=>$brands]);
    }
}
