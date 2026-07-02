<?php

namespace App\Filament\Resources\HasilOptimasis;

use App\Filament\Resources\HasilOptimasis\Pages\CreateHasilOptimasi;
use App\Filament\Resources\HasilOptimasis\Pages\EditHasilOptimasi;
use App\Filament\Resources\HasilOptimasis\Pages\ListHasilOptimasis;
use App\Filament\Resources\HasilOptimasis\Schemas\HasilOptimasiForm;
use App\Filament\Resources\HasilOptimasis\Tables\HasilOptimasisTable;
use App\Models\HasilOptimasi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;
use Illuminate\Support\Facades\Auth;
class HasilOptimasiResource extends Resource
{
    protected static ?string $model = HasilOptimasi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Hasil Optimasi';

    protected static ?string $modelLabel = 'Hasil Optimasi';

    protected static ?string $pluralModelLabel = 'Laporan Hasil Optimasi';

    protected static string|UnitEnum|null $navigationGroup = 'Optimasi Tiket';

    protected static ?int $navigationSort = 2;

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
        return HasilOptimasiForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HasilOptimasisTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Jumlah laporan hasil optimasi';
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHasilOptimasis::route('/'),
            'create' => CreateHasilOptimasi::route('/create'),
            'edit' => EditHasilOptimasi::route('/{record}/edit'),
        ];
    }
}