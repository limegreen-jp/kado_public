<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\KadoController;

class SelectUserAndDate extends Component
{
    public $user_id;
    public $term_id;
    public $users;
    public $terms;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($userId=null, $termId=null)
    {
        $this->user_id = $userId;
        $this->term_id = $termId;

        $this->users = DB::table('users')
                    ->select('id', 'name')
                    ->get();

        $this->terms = DB::table('terms')
                    ->select('id', 'term_name')
                    ->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.select-user-and-date');
    }
}
