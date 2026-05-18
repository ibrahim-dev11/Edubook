<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Component;

class CustomLogin extends BaseLogin
{
    public function getHeading(): string | Htmlable
    {
        return 'چوونە ژوورەوە';
    }

    public function getSubmitButtonLabel(): string | Htmlable
    {
        return 'چوونە ژوورەوە';
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->label('ئیمەیڵ');
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->label('وشەی نهێنی');
    }

    protected function getRememberFormComponent(): Component
    {
        return parent::getRememberFormComponent()
            ->label('من بەبیر بهێنەرەوە');
    }
}
