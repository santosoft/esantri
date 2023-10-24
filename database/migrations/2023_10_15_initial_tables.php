<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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

        Schema::create('mutqins', function (Blueprint $table) {
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
        });

        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->nullable();
            $table->foreignId('muhaffizh_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->foreignId('group_id')->nullable();
            $table->integer('tahun')->unsigned()->nullable();
            $table->smallInteger('bulan')->unsigned()->nullable();
            $table->smallInteger('pekan')->unsigned()->nullable();
            $table->smallInteger('hadir')->unsigned()->nullable();
            $table->smallInteger('izin')->unsigned()->nullable();
            $table->smallInteger('sakit')->unsigned()->nullable();
            $table->smallInteger('alpha')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
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
        Schema::dropIfExists('mutqins');
        Schema::dropIfExists('tahfizhs');
        Schema::dropIfExists('tahsins');
        Schema::dropIfExists('matans');
        Schema::dropIfExists('absens');
    }
};
