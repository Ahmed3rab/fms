<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Rawilk\ProfileFilament\Auth\Login\Concerns\HandlesLoginForm;

class Login extends BaseLogin
{
    use HandlesLoginForm;
}
