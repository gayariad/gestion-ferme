<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vente;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $ventes = Vente::orderBy('date_vente', 'desc')->get();
        $startOfWeek = Carbon::now()->startOfWeek();
        $chiffreAffaireHebdo = Vente::where('date_vente', '>=', $startOfWeek)
                                    ->sum('total_vente');

        $startOfMonth = Carbon::now()->startOfMonth();
        $chiffreAffaireMensuel = Vente::where('date_vente', '>=', $startOfMonth)
                                      ->sum('total_vente');

        $objectifMensuel = 800.00;

        $ventesParProduit = DB::table('vente')
            ->join('vendu', 'vente.id_vente', '=', 'vendu.id_vente')
            ->join('produit', 'vendu.id_produit', '=', 'produit.id_produit')
            ->select('produit.nom_produit', DB::raw('SUM(vente.quantite_produit) as total_quantite'))
            ->groupBy('produit.id_produit', 'produit.nom_produit')
            ->get();

        return view('dashboard.index', compact(
            'ventes',
            'chiffreAffaireHebdo',
            'chiffreAffaireMensuel',
            'objectifMensuel',
            'ventesParProduit'
        ));
    }
}
