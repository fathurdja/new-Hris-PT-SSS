<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\AttendanceExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.name')->searchable()->label('Employee'),
                Tables\Columns\ImageColumn::make('attendance_photo')->label('Selfie'),
                Tables\Columns\TextColumn::make('check_in_time')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('check_out_time')->dateTime(),
                Tables\Columns\TextColumn::make('latitude')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('longitude')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('division')
                    ->relationship('employee.division', 'name')
                    ->label('Division'),
                Filter::make('check_in_time')
                    ->form([
                        DatePicker::make('check_in_from'),
                        DatePicker::make('check_in_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['check_in_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('check_in_time', '>=', $date),
                            )
                            ->when(
                                $data['check_in_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('check_in_time', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['check_in_from'] ?? null) {
                            $indicators['from'] = 'From: ' . \Carbon\Carbon::parse($data['check_in_from'])->toFormattedDateString();
                        }
                        if ($data['check_in_until'] ?? null) {
                            $indicators['until'] = 'Until: ' . \Carbon\Carbon::parse($data['check_in_until'])->toFormattedDateString();
                        }
                        return $indicators;
                    })
            ])
            ->headerActions([
                ExportAction::make()->exporter(AttendanceExporter::class),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->exporter(AttendanceExporter::class),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAttendances::route('/'),
        ];
    }
}
