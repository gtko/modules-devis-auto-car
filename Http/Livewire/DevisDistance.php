<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;

class DevisDistance extends Component
{

    public array $distance;

    public function mount(array $distance){
        $this->distance = $distance;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('devisautocar::livewire.devis-distance');
    }
}
