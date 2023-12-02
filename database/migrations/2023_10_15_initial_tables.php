<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 32)->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 64);
            $table->foreignId('muhaffizh_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('muhaffizh_id', 'groups_muhaffizh_id_foreign')->references('id')->on('muhaffizhs')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('unit_id', 'groups_unit_id_foreign')->references('id')->on('units')->onDelete('set null')->onUpdate('set null');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable();
            $table->string('email')->unique();
            $table->string('username', 128)->unique();
            $table->string('password', 128)->nullable();
            $table->string('role', 32)->nullable()->comment('Admin/Manajemen/Muhaffizh/Walisantri/Santri');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('muhaffizhs', function (Blueprint $table) {
            $table->id();
            $table->string('no_induk', 16)->nullable();
            $table->string('nama', 64)->nullable();
            $table->string('alamat')->nullable();
            $table->string('tempat_lahir', 64)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_hp', 16)->nullable();
            $table->string('pendidikan_terakhir', 64)->nullable()->comment("SMK/D3/S1/S2");
            $table->date('mulai_bertugas')->nullable();
            $table->string('angkatan_kelas', 32)->nullable();
            $table->boolean('aktif')->default(true);
            $table->foreignId('unit_id')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('group_id', 'muhaffizhs_group_id_foreign')->references('id')->on('groups')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('unit_id', 'muhaffizhs_unit_id_foreign')->references('id')->on('units')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('user_id', 'muhaffizhs_user_id_foreign')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
        });

        Schema::create('santris', function (Blueprint $table) {
            $table->id();
            $table->string('no_induk', 16)->nullable();
            $table->string('nama', 64);
            $table->string('alamat')->nullable();
            $table->string('tempat_lahir', 64)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->set('gender', ['Laki', 'Perempuan']);
            $table->string('nama_ayah', 64)->nullable();
            $table->string('nama_ibu', 64)->nullable();
            $table->string('no_hp', 16)->nullable();
            $table->date('mulai_belajar')->nullable();
            $table->string('angkatan_kelas', 32)->nullable();
            $table->string('grade', 16)->nullable();
            $table->string('level_santri', 16)->nullable();
            $table->string('foto', 255)->nullable();
            $table->foreignId('group_id')->nullable();
            $table->foreignId('muhaffizh_id')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('group_id', 'santris_group_id_foreign')->references('id')->on('groups')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('muhaffizh_id', 'santris_muhaffizh_id_foreign')->references('id')->on('muhaffizhs')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('user_id', 'santris_user_id_foreign')->references('id')->on('users')->onDelete('set null')->onUpdate('set null');
        });

        Schema::create('pekans', function (Blueprint $table) {
            $table->unsignedInteger('id');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedTinyInteger('bulan');
            $table->unsignedTinyInteger('pekan');
            $table->date('tgl_awal');
            $table->date('tgl_akhir');
            $table->timestamps();

            $table->primary('id');
        });

        /* Schema::create('mutqins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->nullable();
            $table->foreignId('muhaffizh_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->integer('tahun')->unsigned()->nullable();
            $table->smallInteger('bulan')->unsigned()->nullable();
            $table->smallInteger('pekan')->unsigned()->nullable();
            $table->smallInteger('halaman')->unsigned()->nullable();
            $table->string('total_mutqin')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('group_id', 'mutqins_group_id_foreign')->references('id')->on('groups')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('muhaffizh_id', 'mutqins_muhaffizh_id_foreign')->references('id')->on('muhaffizhs')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('santri_id', 'mutqins_santri_id_foreign')->references('id')->on('santris')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('unit_id', 'mutqins_unit_id_foreign')->references('id')->on('units')->onDelete('set null')->onUpdate('set null');
        });

        Schema::create('tahfizhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->nullable();
            $table->foreignId('muhaffizh_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->integer('tahun')->unsigned()->nullable();
            $table->smallInteger('bulan')->unsigned()->nullable();
            $table->smallInteger('pekan')->unsigned()->nullable();
            $table->smallInteger('juz')->unsigned()->nullable();
            $table->smallInteger('halaman')->unsigned()->nullable();
            $table->string('posisi_terakhir', 128)->nullable();
            $table->string('total_tahfizh', 128)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('group_id', 'setorans_group_id_foreign')->references('id')->on('groups')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('muhaffizh_id', 'setorans_muhaffizh_id_foreign')->references('id')->on('muhaffizhs')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('santri_id', 'setorans_santri_id_foreign')->references('id')->on('santris')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('unit_id', 'setorans_unit_id_foreign')->references('id')->on('units')->onDelete('set null')->onUpdate('set null');
        });

        Schema::create('tahsins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->nullable();
            $table->foreignId('muhaffizh_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->integer('tahun')->unsigned()->nullable();
            $table->smallInteger('bulan')->unsigned()->nullable();
            $table->smallInteger('pekan')->unsigned()->nullable();
            $table->smallInteger('level_santri')->default(0);
            $table->string('capaian', 64)->nullable();
            $table->string('posisi_terakhir', 128)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('group_id', 'tahsins_ibfk_1')->references('id')->on('groups')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('muhaffizh_id', 'tahsins_ibfk_2')->references('id')->on('muhaffizhs')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('santri_id', 'tahsins_ibfk_3')->references('id')->on('santris')->onDelete('set null')->onUpdate('set null');
            // $table->foreign('unit_id', 'tahsins_ibfk_4')->references('id')->on('units')->onDelete('set null')->onUpdate('set null');
        });

        Schema::create('matans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->nullable();
            $table->foreignId('muhaffizh_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->integer('tahun')->unsigned()->nullable();
            $table->smallInteger('bulan')->unsigned()->nullable();
            $table->smallInteger('pekan')->unsigned()->nullable();
            $table->string('matan_jazari', 128)->nullable();
            $table->timestamps();
            $table->softDeletes();
        }); */

        Schema::create('setorans', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pekan_id');
            $table->foreignId('santri_id')->nullable();
            $table->foreignId('muhaffizh_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->smallInteger('level_santri')->default(0);
            $table->smallInteger('mutqin_halaman')->unsigned()->nullable();
            $table->string('total_mutqin')->nullable();
            $table->smallInteger('tahfizh_juz')->unsigned()->nullable();
            $table->smallInteger('tahfizh_halaman')->unsigned()->nullable();
            $table->string('tahfizh_posisi_terakhir', 128)->nullable();
            $table->string('total_tahfizh', 128)->nullable();
            $table->string('tahsin_capaian', 64)->nullable();
            $table->string('tahsin_posisi_terakhir', 128)->nullable();
            $table->string('matan_jazari', 128)->nullable();
            $table->smallInteger('absen_hadir')->unsigned()->nullable();
            $table->smallInteger('absen_izin')->unsigned()->nullable();
            $table->smallInteger('absen_sakit')->unsigned()->nullable();
            $table->smallInteger('absen_alpha')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pekan_id', 'setorans_fk_pekans')->references('id')->on('pekans')->restrictOnDelete()->cascadeOnUpdate();
            $table->index('santri_id');
            $table->index('muhaffizh_id');
            $table->index('unit_id');
            $table->index('group_id');
        });

        /* Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pekan_id');
            $table->foreignId('santri_id')->nullable();
            $table->foreignId('muhaffizh_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->smallInteger('hadir')->unsigned()->nullable();
            $table->smallInteger('izin')->unsigned()->nullable();
            $table->smallInteger('sakit')->unsigned()->nullable();
            $table->smallInteger('alpha')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pekan_id', 'absens_fk_pekans')->references('id')->on('pekans')->restrictOnDelete()->cascadeOnUpdate();
            $table->index('santri_id');
            $table->index('muhaffizh_id');
            $table->index('unit_id');
            $table->index('group_id');
        }); */

        DB::statement("CREATE OR REPLACE VIEW matans AS SELECT id,pekan_id,santri_id,muhaffizh_id,unit_id,group_id,level_santri,
            matan_jazari,created_at,updated_at,deleted_at FROM setorans");
        DB::statement("CREATE OR REPLACE VIEW mutqins AS SELECT id,pekan_id,santri_id,muhaffizh_id,unit_id,group_id,level_santri,
            mutqin_halaman,total_mutqin,created_at,updated_at,deleted_at FROM setorans");
        DB::statement("CREATE OR REPLACE VIEW tahfizhs AS SELECT id,pekan_id,santri_id,muhaffizh_id,unit_id,group_id,level_santri,
            tahfizh_juz,tahfizh_halaman,tahfizh_posisi_terakhir,total_tahfizh,
            created_at,updated_at,deleted_at FROM setorans");
        DB::statement("CREATE OR REPLACE VIEW tahsins AS SELECT id,pekan_id,santri_id,muhaffizh_id,unit_id,group_id,level_santri,
            tahsin_capaian,tahsin_posisi_terakhir,created_at,updated_at,deleted_at FROM setorans");
        DB::statement("CREATE OR REPLACE VIEW absens AS SELECT id,pekan_id,santri_id,muhaffizh_id,unit_id,group_id,level_santri,
            absen_hadir,absen_izin,absen_sakit,absen_alpha,created_at,updated_at,deleted_at FROM setorans");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('users');
        Schema::dropIfExists('muhaffizhs');
        Schema::dropIfExists('santris');
        // DB::statement("DROP VIEW IF EXISTS mutqins");
        // DB::statement("DROP VIEW IF EXISTS tahfizhs");
        // DB::statement("DROP VIEW IF EXISTS tahsins");
        // DB::statement("DROP VIEW IF EXISTS matans");
        // DB::statement("DROP VIEW IF EXISTS absens");
        Schema::dropIfExists('mutqins');
        Schema::dropIfExists('tahfizhs');
        Schema::dropIfExists('tahsins');
        Schema::dropIfExists('matans');
        Schema::dropIfExists('setorans');
        Schema::dropIfExists('absens');
        Schema::dropIfExists('pekans');
    }
};
