<?php

namespace Database\Seeders;

use App\Models\Absen;
use App\Models\Group;
use App\Models\Matan;
use App\Models\Muhaffizh;
use App\Models\Mutqin;
use App\Models\Santri;
use App\Models\Tahfizh;
use App\Models\Tahsin;
use App\Models\Unit;
use App\Models\User;
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
        DB::table('users')->truncate();
        DB::table('units')->truncate();
        DB::table('groups')->truncate();
        DB::table('muhaffizhs')->truncate();
        DB::table('santris')->truncate();
        DB::table('tahfizhs')->truncate();
        DB::table('tahsins')->truncate();
        DB::table('mutqins')->truncate();
        DB::table('matans')->truncate();
        DB::table('absens')->truncate();

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
        Tahfizh::factory(20)->recycle($santris)->recycle($groups)->create();
        Tahsin::factory(20)->recycle($santris)->recycle($groups)->create();
        Mutqin::factory(20)->recycle($santris)->recycle($groups)->create();
        Matan::factory(20)->recycle($santris)->recycle($groups)->create();
        Absen::factory(20)->recycle($santris)->recycle($groups)->create();
    }
}
