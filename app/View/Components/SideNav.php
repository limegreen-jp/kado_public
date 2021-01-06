<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\KadoController;

class SideNav extends Component
{
    public $user_id;
    public $current_year;
    public $current_month;
    public $current_term_id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $kadoController = new KadoController;
        
        $this->user_id = Auth::id();
        $current_year_month = date('Y/n');
        $this->current_year = explode('/', $current_year_month)[0];
        $this->current_month = explode('/', $current_year_month)[1];
        $current_term_name = $kadoController->convertYearMonthIntoTeam($current_year_month);
        $this->current_term_id = $kadoController->getTermId($current_term_name);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.side-nav');
    }
}
