<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Institution;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'بەکارهێنەران';

    protected static ?string $modelLabel = 'بەکارهێنەر';

    protected static ?string $pluralModelLabel = 'بەکارهێنەران';

    protected static ?string $navigationGroup = 'سەرەکی';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('is_approved', false)->where('is_admin', false)->where('user_type', 'portal')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::where('is_approved', false)->where('is_admin', false)->where('user_type', 'portal')->count() > 0 ? 'warning' : 'info';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('زانیاری بەکارهێنەر')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('ناو')
                            ->required()
                            ->markAsRequired(false)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('ئیمەیڵ')
                            ->email()
                            ->required()
                            ->markAsRequired(false)
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label('وشەی نهێنی')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->markAsRequired(false)
                            ->maxLength(255),

                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('ناو')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('email')
                    ->label('ئیمەیڵ')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('پەسەندکراوە')
                    ->boolean()
                    ->sortable()
                    ->toggleable()
                    ->getStateUsing(fn (User $record) => $record->user_type === 'portal' || $record->user_type === null ? $record->is_approved : null)
                    ->trueIcon('heroicon-m-check-badge')
                    ->falseIcon('heroicon-m-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('بەرواری تۆمارکردن')
                    ->dateTime('Y/m/d H:i')\\\\\\
                    ->sortable()
                    ->icon('heroicon-o-calendar'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('پەسەندکراوە')
                    ->trueLabel('چالاک')
                    ->falseLabel('چالاک نەکراو'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggleApproval')
                    ->label(fn (User $record) => $record->is_approved ? 'ناچالاککردن' : 'چالاککردن')
                    ->icon(fn (User $record) => $record->is_approved ? 'heroicon-m-x-circle' : 'heroicon-m-check-badge')
                    ->color(fn (User $record) => $record->is_approved ? 'danger' : 'success')
                    ->button()
                    ->requiresConfirmation(fn (User $record) => $record->is_approved)
                    ->visible(fn (User $record) => $record->user_type === 'portal' || $record->user_type === null)
                    ->form(fn (User $record) => $record->is_approved ? [] : [
                        Forms\Components\Select::make('institution_id')
                            ->label('دامەزراوەی پەیوەندیدار')
                            ->options(
                                Institution::orderBy('nku')
                                    ->get()
                                    ->mapWithKeys(fn (Institution $i) => [$i->id => $i->nku ?: $i->nen ?: "#{$i->id}"])
                            )
                            ->searchable()
                            ->placeholder('هیچ دامەزراوەیەک نەگەڕێنەوە...')
                            ->nullable(),
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update(['is_approved' => !$record->is_approved]);

                        // If activating and an institution was selected, link it to this user
                        if ($record->is_approved && !empty($data['institution_id'])) {
                            Institution::where('id', $data['institution_id'])
                                ->update(['user_id' => $record->id]);
                        }

                        \Filament\Notifications\Notification::make()
                            ->title($record->is_approved ? 'هەژمار چالاککرا' : 'هەژمار ناچالاککرا')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('sendNotification')
                        ->label('ناردنی نۆتیفیکەیشن')
                        ->icon('heroicon-o-chat-bubble-left-ellipsis')
                        ->color('success')
                        ->form([
                            Forms\Components\TextInput::make('title')
                                ->label('ناونیشان')
                                ->required()
                                ->default('پەیامێک لە لایەن بەڕێوەبەر'),
                            Forms\Components\Textarea::make('message')
                                ->label('پەیام')
                                ->required()
                                ->rows(3),
                        ])
                        ->action(function (User $record, array $data): void {
                            $firebase = app(\App\Services\FirebaseNotificationService::class);
                            
                            // Save to database
                            $record->notify(new \App\Notifications\AdminMessage(
                                $data['title'],
                                $data['message']
                            ));
                            
                            // Send via Firebase if user has FCM token
                            if ($record->fcm_token && $record->notifications_enabled) {
                                $firebase->sendToToken(
                                    $record->fcm_token,
                                    $data['title'],
                                    $data['message']
                                );
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('نۆتیفیکەیشن بە سەرکەوتوویی نێردرا')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\EditAction::make()->label('دەستکاری'),
                    Tables\Actions\DeleteAction::make()->label('سڕینەوە'),
                ]),
            ])
            ->bulkActions([])
            ->striped();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
