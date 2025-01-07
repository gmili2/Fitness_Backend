<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('scans', function (Blueprint $table) {
            $table->id(); // ID unique pour chaque scan
            $table->foreignId('client_id') // Référence à la table clients
                ->constrained('clients') // Clé étrangère vers la table `clients`
                ->onDelete('cascade'); // Supprime les scans si le client est supprimé
            $table->string('barcode'); // Code-barre scanné
            $table->timestamp('scanned_at')->useCurrent(); // Date et heure du scan
            $table->timestamps(); // created_at et updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scans');
    }

}
