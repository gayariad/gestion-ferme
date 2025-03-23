<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleIdToUsersTable extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Ajoute la colonne role_id avec une valeur par défaut de 1
        $table->unsignedBigInteger('role_id')->after('id')->default(1);
        // Définit la contrainte de clé étrangère
        $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
    });
}

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprime la contrainte et la colonne
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
}
