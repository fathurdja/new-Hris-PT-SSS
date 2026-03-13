<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\UserExporter;
use App\Filament\Imports\UserImporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Employees';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Employee Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')->required()->label('Full Name'),
                        Forms\Components\TextInput::make('email')->email()->required(),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->hiddenOn('edit')
                            ->dehydrated(fn ($state) => filled($state)),
                        Forms\Components\TextInput::make('nip')->required()->unique(ignoreRecord: true),
                        Forms\Components\FileUpload::make('photo')->image()->directory('employees')->avatar(),
                        Forms\Components\Select::make('division_id')
                            ->relationship('division', 'name')
                            ->required()
                            ->label('Division'),
                        Forms\Components\Textarea::make('address')->columnSpanFull(),
                        Forms\Components\TextInput::make('phone')->tel(),
                        Forms\Components\Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('nip')->searchable(),
                Tables\Columns\TextColumn::make('division.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('roles.name')->badge(),
            ])
            ->filters([
                //
            ])
            ->groups([
                'division.name',
            ])
            ->headerActions([
                ExportAction::make()->exporter(UserExporter::class),
                ImportAction::make()->importer(UserImporter::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->exporter(UserExporter::class),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
