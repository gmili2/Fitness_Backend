<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function updateDatePointageSortie(Request $request, $id)
    {
        $request->validate([
            'date_pointage_sortie' => 'required|date',
        ]);

        $scan = Scan::findOrFail($id);
        $scan->date_pointage_sortie = $request->input('date_pointage_sortie');
        $scan->save();

        return response()->json(['message' => 'Date de pointage sortie mise à jour avec succès.']);
    }
}
