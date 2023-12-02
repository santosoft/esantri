<?php

namespace Database\Seeders;

use App\Models\Absen;
use App\Models\Group;
// use App\Models\Matan;
use App\Models\Muhaffizh;
use App\Models\Pekan;
// use App\Models\Mutqin;
use App\Models\Santri;
use App\Models\Setoran;
// use App\Models\Tahfizh;
// use App\Models\Tahsin;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $truncTables = [
            'users','units','groups','muhaffizhs','santris'
            // ,'mutqins','tahfizhs','tahsins','matans','absens'
            ,'setorans','pekans'
        ];
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($truncTables as $t) DB::table($t)->truncate();
        DB::statement("SET sql_mode = 'STRICT_ALL_TABLES';");

        User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'role' => 'Admin'
          ]);

        User::factory(1)->role('Manajemen')->create();

        $units = Unit::upsert([
            ['id' => 1, 'nama' => "TKQu", 'keterangan' => "TK Al Qur'an"],
            ['id' => 2, 'nama' => "SDQu", 'keterangan' => "SD Al Qur'an"],
            ['id' => 3, 'nama' => "SMPQu", 'keterangan' => "SMP Al Qur'an"],
            ['id' => 4, 'nama' => "SMAQu", 'keterangan' => "SD Al Qur'an"]
        ], ['id']);

        Muhaffizh::factory(7)->hasGroups(1, fn (array $attributes, Muhaffizh $muhaffizh)
            => ['nama' => substr($muhaffizh->nama, 0, strpos($muhaffizh->nama,' '))])
            ->create();
        $groups = Group::all();
        $santris = Santri::factory(9)->recycle($groups)->create();

        $pekans = Pekan::factory(6)->sequence(function (Sequence $s) {
            $d = Carbon::parse('last wednesday')->subWeeks($s->index);
            $pekan = $d->format('W') - date('W', mktime(0,0,0,$d->format('n'),0,$d->format('Y'))) + 1;
            return [
                'id'    => $d->format('Ym').$pekan,
                'tahun' => $d->format('Y'),
                'bulan' => $d->format('n'),
                'pekan' => $pekan,
                'tgl_awal'  => $d->startOfWeek()->format('Y-m-d'),
                'tgl_akhir' => $d->endOfWeek()->subDays(2)->format('Y-m-d'),
            ];
        })->create();

        // Tahfizh::factory(20)->recycle($santris)->recycle($groups)->create();
        // Tahsin::factory(20)->recycle($santris)->recycle($groups)->create();
        // Mutqin::factory(20)->recycle($santris)->recycle($groups)->create();
        // Matan::factory(20)->recycle($santris)->recycle($groups)->create();
        Setoran::factory(20)->recycle($pekans)->recycle($santris)->recycle($groups)->create();
        // Absen::factory(20)->recycle($pekans)->recycle($santris)->recycle($groups)->create();
    }
}
