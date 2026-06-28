<?php

namespace App\Filament\Portal\Pages;

use App\Models\PersonalAccessToken;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Pages\PageConfiguration;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use UnitEnum;
use Filament\Support\Icons\Heroicon;

/**
 * @extends Page<PageConfiguration>
 */
class ApiTokens extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected string $view = 'filament.portal.pages.api-tokens';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?string $navigationLabel = 'API Tokens';

    protected static string|UnitEnum|null $navigationGroup = 'API';

    protected static ?int $navigationSort = 1;

    public ?string $generatedToken = null;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createToken')
                ->label('Create Token')
                ->icon('heroicon-o-plus')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    CheckboxList::make('abilities')
                        ->options([
                            '*' => 'Full Access',
                            'telemetry.subscribe' => 'Telemetry Subscribe',
                        ])
                        ->columns(2)
                        ->default(['*']),
                    DatePicker::make('expires_at')
                        ->native(false)
                        ->helperText('Leave empty for no expiration'),
                ])
                ->action(function (array $data) {
                    $token = auth()->user()->createToken(
                        $data['name'],
                        $data['abilities'],
                        $data['expires_at'] ? Carbon::createFromDate($data['expires_at']) : null
                    );

                    $this->generatedToken = $token->plainTextToken;
                    $this->form->fill([
                        'generatedToken' => $this->generatedToken,
                    ]);
                    Notification::make()
                        ->title('Token created')
                        ->body('Copy the token now. It will not be shown again.')
                        ->success()
                        ->send();

                }),
        ];
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(auth()->user()->tokens()->getQuery())
            ->heading('')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('abilities')
                    ->state(
                        fn($record)
                        => in_array('*', $record->abilities ?? [])
                            ? 'Full Access'
                            : implode(', ', $record->abilities ?? [])
                    )
                    ->badge(),

                TextColumn::make('last_used_at')
                    ->since()
                    ->placeholder('Never'),

                TextColumn::make('created_at')
                    ->date(),
                TextColumn::make('expires_at')
                    ->date()
                    ->placeholder('Never')
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->recordActions([
                Action::make('revoke')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn(PersonalAccessToken $record) => $record->delete()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
