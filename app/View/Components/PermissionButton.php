<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class PermissionButton extends Component
{
    public $permission;
    public $class;
    public $onclick;
    public $slot;

    public function __construct($permission, $class = 'btn btn-primary', $onclick = '', $slot = '')
    {
        $this->permission = $permission;
        $this->class = $class;
        $this->onclick = $onclick;
        $this->slot = $slot;
    }

    public function render()
    {
        return view('components.permission-button');
    }

    public function shouldRender()
    {
        return Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->hasPermission($this->permission));
    }
}