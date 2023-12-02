<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
// use App\Models\Absen;
// use App\Models\Matan;
// use App\Models\Mutqin;
use App\Models\Santri;
use App\Models\Setoran;
// use App\Models\Tahfizh;
// use App\Models\Tahsin;
use Filament\Resources\Pages\Page;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Support\Collection;

class RaporSantri extends Page
{
    protected static string $resource = SantriResource::class;

    protected ?string $heading = 'Progress Capaian Pembelajaran AL-QURAN';
    protected ?string $subheading = '🕋 PESANTREN PQBS 🕋';
    protected static string $view = 'filament.resources.santri-resource.pages.rapor-santri';

    public ?Santri $santri;
    public ?Setoran $setoran;
  /*   public ?Tahsin $tahsin;
    public ?Tahfizh $tahfizh;
    public ?Mutqin $mutqin;
    public ?Matan $matan;
    public ?Absen $absen; */
    public string $periode, $pekan;

    public function mount(Santri $record, ?string $periode, ?string $pekan): void {
        $this->santri = $record;
        $this->periode = $periode;
        $this->pekan = $pekan;
        $tahun = substr($periode,0,strpos($periode,'-'));
        $bulan = (int) substr($periode,strpos($periode,'-')+1);
        $pekanId = str_replace('-', '', $periode).$pekan;
        $this->setoran = $this->santri->setorans->where('pekan_id', $pekanId)->first();
        /* $this->tahsin = $this->santri->tahsins
            ->where('tahun',$tahun)
            ->where('bulan',$bulan)
            ->first();
        $this->tahfizh = $this->santri->tahfizhs
            ->where('tahun',$tahun)
            ->where('bulan',$bulan)
            ->first();
        $this->mutqin = $this->santri->mutqins
            ->where('tahun',$tahun)
            ->where('bulan',$bulan)
            ->first();
        $this->matan = $this->santri->matans
            ->where('tahun',$tahun)
            ->where('bulan',$bulan)
            ->first();
        $this->absen = $this->santri->absens
            ->where('tahun',$tahun)
            ->where('bulan',$bulan)
            ->first(); */
    }

    public function raporInfolist(Infolist $infolist): Infolist {
        return $infolist
            ->state([
                'bulan'       => date('F', strtotime("{$this->periode}-15")),
                'pekan'       => $this->pekan,
                'nama_santri' => $this->santri->nama,
                'kelas'       => $this->santri->angkatan_kelas,
                'tahsin' => [
                    'level'           => data_get($this->setoran,'level_santri'),
                    'capaian'         => data_get($this->setoran,'tahsin_capaian'),
                    'posisi_terakhir' => data_get($this->setoran,'tahsin_posisi_terakhir'),
                ],
                'tahfizh' => [
                    'grade'           => data_get($this->santri,'grade'),
                    'capaian'         => data_get($this->setoran,'tahfizh_halaman'),
                    'posisi_terakhir' => data_get($this->setoran,'tahfizh_posisi_terakhir'),
                    'jumlah'          => data_get($this->setoran,'total_tahfizh'),
                ],
                'mutqin' => [
                    'capaian' => data_get($this->setoran,'mutqin_halaman'),
                    'jumlah'  => data_get($this->setoran,'total_mutqin'),
                ],
                'matan' => [
                    'matan_jazari' => data_get($this->setoran,'matan_jazari'),
                ],
                'absen' => [
                    'hadir' => data_get($this->setoran,'absen_hadir'),
                    'izin'  => data_get($this->setoran,'absen_izin'),
                    'sakit' => data_get($this->setoran,'absen_sakit'),
                    'alpha' => data_get($this->setoran,'absen_alpha'),
                ],
            ])
            ->schema([
                Section::make()->schema([
                    TextEntry::make('bulan')->label('Bulan:')->inlineLabel(),
                    TextEntry::make('pekan')->label('Pekan:')->inlineLabel(),
                ])->columns(['sm' => 1,'lg' => 4]),
                Section::make()->schema([
                    TextEntry::make('nama_santri')->label('Nama Santri:')->inlineLabel(),
                    TextEntry::make('kelas')->label('Kelas:')->inlineLabel(),
                ])->columns(['sm' => 1,'lg' => 3]),
                Grid::make(2)->schema([
                    Fieldset::make('Tahsin')->schema([
                        TextEntry::make('tahsin.level')->label('Level:')->inlineLabel(),
                        TextEntry::make('tahsin.capaian')->label('Capaian:')->inlineLabel(),
                        TextEntry::make('tahsin.posisi_terakhir')->label('Posisi Terakhir:')
                            ->inlineLabel()->columnSpanFull(),
                    ])->columnSpan(1),
                    Fieldset::make('Tahfizh')->schema([
                        TextEntry::make('tahfizh.grade')->label('Grade:')->inlineLabel(),
                        TextEntry::make('tahfizh.capaian')->label('Capaian:')->inlineLabel(),
                        TextEntry::make('tahfizh.posisi_terakhir')->label('Posisi Terakhir:')->inlineLabel(),
                        TextEntry::make('tahfizh.jumlah')->label('Jumlah:')->inlineLabel(),
                    ])->columnSpan(1),
                    Fieldset::make('Mutqin')->schema([
                        TextEntry::make('mutqin.capaian')->label('Capaian:')->inlineLabel(),
                        TextEntry::make('mutqin.jumlah')->label('Jumlah:')->inlineLabel(),
                    ])->columnSpan(1),
                    Fieldset::make('Matan')->schema([
                        TextEntry::make('matan.matan_jazari')->label('Matan Jazari:')->inlineLabel(),
                    ])->columns(1)->columnSpan(1),
                    Fieldset::make('Kehadiran')->schema([
                        TextEntry::make('absen.hadir')->label('Hadir:')->inlineLabel(),
                        TextEntry::make('absen.izin')->label('Izin:')->inlineLabel(),
                        TextEntry::make('absen.sakit')->label('Sakit:')->inlineLabel(),
                        TextEntry::make('absen.alpha')->label('Alpha:')->inlineLabel(),
                    ])->columnSpan(1),
                ])
            ]);
    }
}
