<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Agenda;

class AgendaController extends Controller
{
    public function index()
    {
        $agenda = Agenda::where('aktif', true)->orderBy('tanggal_mulai')->paginate(12);
        return view('web.agenda.index', compact('agenda'));
    }
}
