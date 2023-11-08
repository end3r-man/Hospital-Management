<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientRecordResource\Pages;
use App\Filament\Resources\PatientRecordResource\RelationManagers;
use App\Models\PatientRecord;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientRecordResource extends Resource
{
    protected static ?string $model = PatientRecord::class;

    protected static ?string $navigationGroup = 'Patient';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    FileUpload::make('records')
                        ->columnSpan(2)
                        ->imageEditor()
                        ->previewable(),
                    Select::make('patient_parents_name')
                        ->relationship('patient_parents', 'parent_name')
                        ->label('Parent')
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('parent_name')
                                ->required(),
                            TextInput::make('parent_mail')
                                ->required()->email(),
                            TextInput::make('parent_number')
                                ->required()->tel(),
                        ])
                        ->columnSpan(1),
                    Select::make('patient_id')
                        ->relationship('patients', 'patient_name')
                        ->label('Patient')
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            TextInput::make('patient_name'),
                            DatePicker::make('patient_dob')
                                ->native(false),
                            TextInput::make('description'),
                            Select::make('patient_parents_id')
                            ->relationship('patient_parents', 'parent_name')
                            ->label('Parent')
                            ->native(false)
                            ->searchable()
                            ->preload()
                        ]),
                    DatePicker::make('appointment_date')
                        ->columnSpan(1)
                        ->native(false),
                    TimePicker::make('appointment_time')
                        ->native(false)
                        ->seconds(false),
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('patient_parents.parent_name')
                    ->searchable(),
                TextColumn::make('patients.patient_name')
                    ->searchable(),
                ImageColumn::make('records')
                    ->circular()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatientRecords::route('/'),
            'create' => Pages\CreatePatientRecord::route('/create'),
            'edit' => Pages\EditPatientRecord::route('/{record}/edit'),
        ];
    }
}
