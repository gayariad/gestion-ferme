<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Woofer;
use App\Models\Vente;
use App\Models\Produit;
use App\Models\Atelier;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $totalWoofers = Woofer::count();
        $totalVentes = Vente::count();

        $produitsFaibles = Produit::where('quantite_stock', '<', 10)->count();

        $ateliersAVenir = Atelier::where('etat_atelier', 'en attente')->count();

        $totalProfit = Vente::sum('total_vente'); 
        $revenu = Vente::whereDate('date_vente', Carbon::today())->sum('total_vente');

        $ventesRecentes = Vente::orderBy('date_vente', 'desc')->take(5)->get();

        $produitsLesPlusVendus = DB::table('produit')
            ->join('vendu', 'produit.id_produit', '=', 'vendu.id_produit')
            ->join('vente', 'vendu.id_vente', '=', 'vente.id_vente')
            ->select('produit.nom_produit', DB::raw('SUM(vente.quantite_produit) as total_vendu'))
            ->groupBy('produit.nom_produit')
            ->orderByDesc('total_vendu')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalWoofers', 
            'totalVentes', 
            'totalProfit', 
            'produitsFaibles', 
            'ateliersAVenir', 
            'revenu', 
            'ventesRecentes', 
            'produitsLesPlusVendus'
        ));
    }
}
