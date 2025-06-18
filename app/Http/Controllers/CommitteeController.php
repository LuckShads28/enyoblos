<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Result;
use App\Models\Periode;
use App\Models\Candidate;  
use App\Mail\InfoPemiraMail;
use App\Models\candidatepair;
use Illuminate\Http\Request; 
use App\Jobs\SendInfoPemiraEmail;
use App\Http\Requests\PeriodeRequest;
use Illuminate\Support\Facades\Mail; 

class CommitteeController extends Controller
{
    
    private $candidatepairs;
    private $periode;
    private $result;
    private $user;
    public function __construct(candidatepair $candidatepairs, Periode $periode, Result $result, User $user)
    {
        $this->user = $user;
        $this->periode = $periode;
        $this->candidatepairs = $candidatepairs;
        $this->result = $result;
    }

    public function index()
    {
         
       $committee =  $this->user->where('role', 'panitia')->get();

        $search = null;
        if (request('nim')) {
            $search =  $this->user->where([['role', '!=', 'panitia'], ['nim', request('nim')]])->first();
        }

        return view('master/data_panitia', [
            'title' => 'Data Panitia',
            'committee' => $committee,
            'search' => $search
        ]);
    }

     

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $committee = $this->user->where('nim', $request->nim)->first();

        $committee->update([
            'role' => 'panitia'
        ]);

        return redirect()->route('panitia.index')->with('success', 'Data panitia telah ditambah');
    }

      
    public function deleteSelected(Request $request)
    {

        if ($request->ids) {
            $this->user->whereIn('id', $request->ids)->update([
                'role' => 'user'
            ]);
        }

        return redirect()->route('panitia.index')->with('success', 'Data panitia telah dihapus');
    }

    /**
     * Form info kpu
     */
    public function periode()
    {
        $data = $this->periode->first();
        return view('panitia/pengaturan', [
            'title' => 'Pengaturan KPU',
            'data' => $data
        ]);
    }

    /**
     * Update info kpu
     */
    public function updateperiode(PeriodeRequest $request)
    {
        $validatedData = $request->all();

        $data = $this->periode->first();
        $data->update($validatedData);

        return redirect()->route('pengaturan')->with('success', 'Berhasil diupdate');
    }

    /**
     * Download template untuk import pemilih dari excel
     */
    public function downloadVoterTemplate()
    {
        return response()->download(storage_path('app/template/Template Import Pemilih.xlsx'));
    }

    /**
     * Cek status pemilihan
     */
    public function electionStatus()
    {
        $periode = $this->periode->first();
        return view('panitia/status_pemilihan', [
            'title' => 'Status Pemilihan',
            'periode' => $periode
        ]);
    }

    /**
     * API ambil data pemilihan
     */
    public function getDataElection()
    {
        $candidate = $this->candidatepairs->get();
        $data = [];
        foreach ($candidate as $c) {
            array_push($data, [$c->getDataChairman->user->name." & ".$c->getDataViceChairman->user->name  => $this->result->where('candidat_id', $c->id)->count()]);
        }

        return response()->json($data);
    }

    /**
     * Kirim email info pemira ke user
     */
    public function sendEmail($id)
    {
        $user = $this->user->find($id);

        $mailData = [
            'title' => 'PEMIRA Kahima 2024',
            'body' => 'Informasi mengenai kahima baru tahun 2024',
            'nama' => $user->name,
            'default_password' => $user->nim . '_pemira2024',
            'login_email' => $user->email
        ];

        SendInfoPemiraEmail::dispatch($user, $mailData)->delay(now()->addSeconds(5));

        return redirect()->back()->with('success', 'Berhasil mengirim email ke ' . $user->email);
    }

    /**
     * Kirim email ke semua user
     */
    public function sendAllEmail()
    {
        $users = $this->user->where('role', '!=', 'master')->get();

        foreach ($users as $user) {
            $mailData = [
                'title' => 'PEMIRA Kahima 2024',
                'body' => 'Informasi mengenai kahima baru tahun 2024',
                'nama' => $user->name,
                'default_password' => $user->nim . '_pemira2024',
                'login_email' => $user->email
            ];

             SendInfoPemiraEmail::dispatch($user, $mailData)->delay(now()->addSeconds(5));
        }

        return redirect()->back()->with('success', 'Berhasil mengirim email ke semua user');
    }

    /**
     * Kirim email ke pengguna yang dipilih
     */
    public function sendSelectedEmail(Request $request)
    {
        if (!$request->ids || count($request->ids) === 0) {
            return redirect()->back()->with('error', 'Tidak ada pengguna yang dipilih');
        }

        $users = $this->user->whereIn('id', $request->ids)->get();
        $totalUsers = $users->count();

        foreach ($users as $user) {
            $mailData = [
                'title' => 'PEMIRA Kahima 2024',
                'body' => 'Informasi mengenai kahima baru tahun 2024',
                'nama' => $user->name,
                'default_password' => $user->nim . '_pemira2024',
                'login_email' => $user->email
            ];

            SendInfoPemiraEmail::dispatch($user, $mailData)->delay(now()->addSeconds(5));
        }

        return redirect()->back()->with('success', 'Berhasil mengirim email ke ' . $totalUsers . ' pengguna terpilih');
    }
}
