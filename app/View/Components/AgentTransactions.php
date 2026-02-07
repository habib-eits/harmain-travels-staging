<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AgentTransactions extends Component
{
    /**
     * Create a new component instance.
     *
     *
     */
    public $agents;

    public function __construct($agents)
    {
        $this->agents = $agents;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
   
     public function render()
     {
         return view('components.agent-transactions');
     }
}
