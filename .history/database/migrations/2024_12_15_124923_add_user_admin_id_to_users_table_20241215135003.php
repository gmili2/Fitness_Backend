<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserAdminIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('user_admin_id') // Colonne pour la clé étrangère
                ->nullable() // Peut être NULL si l'utilisateur n'est pas lié à un admin
                ->constrained('user_admins') // Table référencée
                ->onDelete('set null'); // Si un admin est supprimé, le champ devient NULL
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_admin_id']);
            $table->dropColumn('user_admin_id');
        });
    }

}
