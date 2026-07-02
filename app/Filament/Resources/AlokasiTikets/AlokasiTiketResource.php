<?php

namespace App\Filament\Resources\AlokasiTikets;

use App\Filament\Resources\AlokasiTikets\Pages\CreateAlokasiTiket;
use App\Filament\Resources\AlokasiTikets\Pages\EditAlokasiTiket;
use App\Filament\Resources\AlokasiTikets\Pages\ListAlokasiTikets;
use App\Filament\Resources\AlokasiTikets\Schemas\AlokasiTiketForm;
use App\Filament\Resources\AlokasiTikets\Tables\AlokasiTiketsTable;
use App\Models\AlokasiTiket;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;
use Illuminate\Support\Facades\Auth;

class AlokasiTiketResource extends Resource
{
    protected static ?string $model = AlokasiTiket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Alokasi Tiket';

    protected static ?string $modelLabel = 'Alokasi Tiket';

    protected static ?string $pluralModelLabel = 'Data Alokasi Tiket';

    protected static string|UnitEnum|null $navigationGroup = 'Optimasi Tiket';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return Auth::user()?->canViewOptimizationData() ?? false;
    }

    public static function canView(Model $record): bool
    {
        return Auth::user()?->canViewOptimizationData() ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->canManageOptimizationData() ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->canManageOptimizationData() ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return Auth::user()?->canDeleteImportantData() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->canDeleteImportantData() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return AlokasiTiketForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AlokasiTiketsTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Jumlah data alokasi tiket';
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAlokasiTikets::route('/'),
            'create' => CreateAlokasiTiket::route('/create'),
            'edit' => EditAlokasiTiket::route('/{record}/edit'),
        ];
    }
}