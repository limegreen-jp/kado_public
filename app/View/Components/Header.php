<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Header extends Component
{
    public $user_myself;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $user_id = Auth::id();
        $user_name = DB::table('users')
                    ->select('name')
                    ->where('id', '=', $user_id)
                    ->first();

        $this->user_myself = [
            'user_id' => $user_id,
            'user_name' => $user_name->name,
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.header');
    }
}
