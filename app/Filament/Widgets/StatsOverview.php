<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Pasien;
use App\Models\JadwalPemberianObat;
use App\Models\Ruangan;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();  // Mendapatkan pengguna yang login
        $isAdmin = $user->hasRole('super_admin');  // Memeriksa apakah pengguna adalah admin
        $isPerawat = $user->hasRole('perawat');  // Memeriksa apakah pengguna adalah perawat

        $stats = [
            // Total Pasien Terdaftar
            $this->getPasienStat(),

            // Total Perawat Terdaftar (Admin hanya)
            $isAdmin ? $this->getPerawatStat() : null,

            // Total Ruangan (Admin hanya)
            $isAdmin ? $this->getRuanganStat() : null,

            // Pasien yang Diberikan Obat Hari Ini (Admin dan Perawat)
            $this->getPasienDiberikanStat($isAdmin, $isPerawat),

            // Pasien yang Masih Menunggu Hari Ini (Admin dan Perawat)
            $this->getPasienMenungguStat($isAdmin, $isPerawat),
        ];

        // Menghapus nilai null (jika ada)
        return array_filter($stats);
    }

    // Mendapatkan statistik Total Pasien
    protected function getPasienStat(): Stat
    {
        $user = auth()->user(); // Mendapatkan pengguna yang login
        $isAdmin = $user->hasRole('super_admin'); // Memeriksa apakah pengguna adalah admin

        // Jika pengguna adalah admin, tampilkan seluruh data pasien
        $query = Pasien::query();

        // Jika pengguna adalah perawat, filter berdasarkan user_id perawat
        if (!$isAdmin) {
            $query->whereHas('jadwalPemberianObat', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return Stat::make('Total Pasien', $query->count())
            ->description('Jumlah pasien terdaftar')
            ->color('info')
            ->extraAttributes(['class' => 'bg-blue-500 text-white rounded-lg shadow-lg p-6']);
    }

    // Mendapatkan statistik Total Perawat (Hanya Admin)
    protected function getPerawatStat(): ?Stat
    {
        if (auth()->user()->hasRole('super_admin')) {
            return Stat::make('Total Perawat', User::whereHas('roles', fn($query) => $query->where('name', 'perawat'))->count())
                ->description('Jumlah perawat terdaftar')
                ->color('info')
                ->extraAttributes(['class' => 'bg-green-500 text-white rounded-lg shadow-lg p-6']);
        }

        return null; // Tidak menampilkan statistik perawat jika bukan admin
    }

    // Mendapatkan statistik Total Ruangan (Hanya Admin)
    protected function getRuanganStat(): ?Stat
    {
        if (auth()->user()->hasRole('super_admin')) {
            return Stat::make('Total Ruangan', Ruangan::count())
                ->description('Jumlah total Ruangan di PKU')
                ->color('info')
                ->extraAttributes(['class' => 'bg-indigo-500 text-white rounded-lg shadow-lg p-6']);
        }

        return null; // Tidak menampilkan statistik ruangan jika bukan admin
    }

    // Mendapatkan statistik Pasien Diberikan Hari Ini
    protected function getPasienDiberikanStat(bool $isAdmin, bool $isPerawat): ?Stat
    {
        if (!$isAdmin && !$isPerawat) {
            return null; // Tidak tampilkan jika bukan admin dan bukan perawat
        }

        // Admin bisa melihat semua pasien yang diberikan obat hari ini
        $query = JadwalPemberianObat::whereDate('waktu', today())
            ->where('status', 'diberikan');

        // Perawat hanya bisa melihat pasien yang diberikan obat berdasarkan user_id mereka
        if (!$isAdmin) {
            $query->where('user_id', auth()->user()->id);
        }

        return Stat::make('Pasien Diberikan Hari Ini', $query->count())
            ->description('Jumlah pasien yang obatnya sudah diberikan hari ini')
            ->descriptionIcon('heroicon-o-check-circle')
            ->chart([2, 5, 8, 10, 3, 6, 9]) // Contoh grafik
            ->color('success')
            ->extraAttributes(['class' => 'bg-green-700 text-white rounded-lg shadow-lg p-6']);
    }

    // Mendapatkan statistik Pasien Menunggu Hari Ini
    protected function getPasienMenungguStat(bool $isAdmin, bool $isPerawat): ?Stat
    {
        if (!$isAdmin && !$isPerawat) {
            return null; // Tidak tampilkan jika bukan admin dan bukan perawat
        }

        // Admin bisa melihat semua pasien yang menunggu pemberian obat hari ini
        $query = JadwalPemberianObat::whereDate('waktu', today())
            ->where('status', 'waiting');

        // Perawat hanya bisa melihat pasien yang menunggu pemberian obat berdasarkan user_id mereka
        if (!$isAdmin) {
            $query->where('user_id', auth()->user()->id);
        }

        return Stat::make('Pasien Menunggu Hari Ini', $query->count())
            ->description('Jumlah pasien yang masih menunggu pemberian obat hari ini')
            ->descriptionIcon('heroicon-o-clock')
            ->chart([7, 2, 10, 3, 5, 6, 3]) // Contoh grafik
            ->color('warning')
            ->extraAttributes(['class' => 'bg-yellow-500 text-white rounded-lg shadow-lg p-6']);
    }
}
