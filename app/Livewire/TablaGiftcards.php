<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Giftcard;

class TablaGiftcards extends Component
{
    public $giftcards;

    public function mount()
    {
        $this->giftcards = Giftcard::all();
    }

    public function render()
    {
        return view('livewire.tabla-giftcards');
    }
}
