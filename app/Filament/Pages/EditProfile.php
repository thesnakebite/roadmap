<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EditProfile extends \Filament\Auth\Pages\EditProfile
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                TextInput::make('phone')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[+]?[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->minLength(18)
                    ->maxLength(18)
                    ->prefixIcon('heroicon-o-phone')
                    ->mask('(+34) 999 99 99 99')
                    ->required(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                $this->getCurrentPasswordFormComponent(),
            ]);
    }
}
