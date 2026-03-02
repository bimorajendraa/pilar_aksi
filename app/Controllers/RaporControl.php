<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Acara;
use App\Models\Hadir;
use App\Models\Nilai;
use App\Models\Pengurus;
use App\Models\Rapor;
use App\Modules\Breadcrumbs\Breadcrumbs;
use CodeIgniter\HTTP\RedirectResponse;
use DateTime;

class RaporControl extends BaseController
{
    public Breadcrumbs $breadcrumbs;
    public Pengurus $pengurus;
    public Nilai $nilai;
    public Acara $acara;
    public Hadir $hadir;
    public Rapor $rapor;
    public int $id_pengurus;

    public function __construct()
    {
        $this->breadcrumbs = new Breadcrumbs();
        $this->pengurus = new Pengurus();
        $this->nilai = new Nilai();
        $this->acara = new Acara();
        $this->hadir = new Hadir();
        $this->rapor = new Rapor();
        $this->id_pengurus = session("id_pengurus");
    }

    public function generate_data_nilai() 
    {
        // Menggunakan model Pengurus
        $model = new \App\Models\Pengurus();

        // Mengambil semua data pengurus dari tabel
        $semuaPengurus = $model->findAll();

        // Mengambil setiap id dari data pengurus
        $semuaIdPengurus = array_column($semuaPengurus, 'id_pengurus');

        // dd($semuaIdPengurus); // Debug (optional

        foreach ($semuaIdPengurus as $pengurus) {
            // Generate data for 3 bulan
            for ($id_bulan = 1; $id_bulan <= 3; $id_bulan++) {
                // Generate 5 indikator setiap bulan
                for ($id_indikator = 1; $id_indikator <= 5; $id_indikator++) {
                    $query = $this->nilai->insert([
                        "id_bulan" => $id_bulan,
                        "id_pengurus" => $pengurus,
                        "id_indikator" => $id_indikator,
                        "nilai" => 0,
                        "nilai_a" => 0,
                        "nilai_b" => 0,
                    ]);
                }
            }
        }
        return ;
    }

    public function generate_data_rapor() 
    {
        // Menggunakan model Pengurus
        $model = new \App\Models\Pengurus();

        // Mengambil semua data pengurus dari tabel
        $semuaPengurus = $model->findAll();

        // Mengambil setiap id dari data pengurus
        $semuaIdPengurus = array_column($semuaPengurus, 'id_pengurus');

        //dd($semuaIdPengurus); // Debug (optional

        foreach ($semuaIdPengurus as $pengurus) {
            // Generate data for 3 bulan
            for ($id_bulan = 1; $id_bulan <= 3; $id_bulan++) {
                // Isi kosongan rapor setiap bulan
                    $query = $this->rapor->insert([
                        "id_pengurus" => $pengurus,
                        "id_bulan" => $id_bulan,
                        "umpan_balik" => " ",
                    ]);
            }
        }
        return ;
    }

    public function index(): string
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Daftar Rapor", "/admin/rapor/dashboard");
        $breadcrumbs = $this->breadcrumbs->render();

        $query1 = $this->pengurus->where("id_pengurus",$this->id_pengurus)
            ->first();
        // dd($query1->id_departemen);
        if($this->id_pengurus < 20000)
        {
            $query2 = $this->nilai->select(["nilai.id_pengurus","nama","jabatan","nama_departemen","nilai.id_bulan","jenis","CAST(AVG(nilai) AS DOUBLE) AS nilai"])
                ->join("indikator","nilai.id_indikator = indikator.id_indikator")
                ->join("pengurus","nilai.id_pengurus = pengurus.id_pengurus")
                ->join("mhs","pengurus.nrp = mhs.nrp")
                ->join("departemen","pengurus.id_departemen = departemen.id_departemen")
                ->groupBy("jenis")
                ->groupBy("nilai.id_pengurus")
                ->groupBy("nilai.id_bulan")
                ->orderBy("pengurus.id_departemen")
                ->orderBy("nama")
                ->orderBy("nilai.id_bulan")
                ->orderBy("jenis")
                ->get()
                ->getResult();
            
            if($query2 == null){
                $generate_data = $this->generate_data_nilai();
                return view("admin/rapor/index",
                ["data" => $query2, "breadcrumbs" => $breadcrumbs]);
            }
                
            return view("admin/rapor/index",
                ["data" => $query2, "breadcrumbs" => $breadcrumbs]);
        }

        if($this->id_pengurus < 40000)
        {
            $query3 = $this->nilai->select(["nilai.id_pengurus","nama","jabatan","nama_departemen","nilai.id_bulan","jenis","CAST(AVG(nilai) AS DOUBLE) AS nilai"])
                ->where("pengurus.id_departemen",$query1->id_departemen)
                ->join("indikator","nilai.id_indikator = indikator.id_indikator")
                ->join("pengurus","nilai.id_pengurus = pengurus.id_pengurus")
                ->join("mhs","pengurus.nrp = mhs.nrp")
                ->join("departemen","pengurus.id_departemen = departemen.id_departemen")
                ->groupBy("jenis")
                ->groupBy("nilai.id_pengurus")
                ->groupBy("nilai.id_bulan")
                ->orderBy("pengurus.id_departemen")
                ->orderBy("nama")
                ->orderBy("nilai.id_bulan")
                ->orderBy("jenis")
                ->get()
                ->getResult();

                if($query3 == null){
                    $generate_data = $this->generate_data_nilai();
                    return view("admin/rapor/index",
                    ["data" => $query3, "breadcrumbs" => $breadcrumbs]);
                }   
            return view("admin/rapor/index",
                ["data" => $query3, "breadcrumbs" => $breadcrumbs]);
        }
        return view("errors/404");
    }

    public function isi(): string
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Isi Penilaian", "/admin/rapor/isi");
        $breadcrumbs = $this->breadcrumbs->render();

        $query1 = $this->pengurus->where("id_pengurus",$this->id_pengurus)
            ->first();

        if($this->id_pengurus < 20000)
        {
            $query2 = $this->nilai->select(["nama", "nilai.id_pengurus", "nama_departemen", "nilai.id_indikator", "nilai.id_bulan", "nilai"])
                ->join("indikator", "nilai.id_indikator = indikator.id_indikator")
                ->join("pengurus", "nilai.id_pengurus = pengurus.id_pengurus")
                ->join("departemen", "pengurus.id_departemen = departemen.id_departemen")
                ->join("mhs", "pengurus.nrp = mhs.nrp")
                ->orderBy("pengurus.id_departemen")
                ->orderBy("nama")
                ->orderBy("nilai.id_bulan")
                ->orderBy("id_indikator")
                ->get()
                ->getResult();
            return view("admin/rapor/isi",
                ["data" => $query2, "breadcrumbs" => $breadcrumbs]);
        }

        if($this->id_pengurus < 40000)
        {
            $query3 = $this->nilai->select(["nama", "nilai.id_pengurus", "nama_departemen", "nilai.id_indikator", "nilai.id_bulan", "nilai"])
                ->where("pengurus.id_departemen",$query1->id_departemen)
                ->join("indikator", "nilai.id_indikator = indikator.id_indikator")
                ->join("pengurus", "nilai.id_pengurus = pengurus.id_pengurus")
                ->join("departemen", "pengurus.id_departemen = departemen.id_departemen")
                ->join("mhs", "pengurus.nrp = mhs.nrp")
                ->orderBy("pengurus.id_departemen")
                ->orderBy("nama")
                ->orderBy("nilai.id_bulan")
                ->orderBy("id_indikator")
                ->get()
                ->getResult();
            return view("admin/rapor/isi",
                ["data" => $query3, "breadcrumbs" => $breadcrumbs]);
        }
        return view("errors/404");
    }

    public function isi_auto($id_pengurus,$id_bulan): RedirectResponse
    {
        $query1 = $this->pengurus->select(["id_departemen","nama","mhs.nrp"])
            ->where("id_pengurus",$id_pengurus)
            ->join("mhs","pengurus.nrp = mhs.nrp")
            ->first();

        $awal = (new DateTime("0000-00-00 00:00:00"))->format("y-m-d H:i:s");
        $akhir = (new DateTime("0000-00-00 00:00:00"))->format("y-m-d H:i:s");
        switch($id_bulan)
        {
            case(1):
                $awal = (new DateTime("2025-04-01 00:00:00"))->format("y-m-d H:i:s");
                $akhir = (new DateTime("2025-04-30 23:59:59"))->format("y-m-d H:i:s"); break;
            case(2):
                $awal = (new DateTime("2025-05-01 00:00:00"))->format("y-m-d H:i:s");
                $akhir = (new DateTime("2025-05-31 23:59:59"))->format("y-m-d H:i:s"); break;
            case(3):
                $awal = (new DateTime("2025-06-01 00:00:00"))->format("y-m-d H:i:s");
                $akhir = (new DateTime("2025-06-30 23:59:59"))->format("y-m-d H:i:s"); break;
        }
        $nilai1b = $this->acara->where("id_departemen",$query1->id_departemen)
            ->where("tipe !=",3)
            ->where("tanggal >=",$awal)
            ->where("tanggal <=",$akhir)
            ->countAllResults();

        $nilai2a = $this->hadir->where("nrp",$query1->nrp)
            ->where("tipe","1")
            ->where("tanggal >=",$awal)
            ->where("tanggal <=",$akhir)
            ->join("acara","hadir.kode_acara = acara.kode_acara")
            ->countAllResults();
        $nilai2b = $this->acara->where("id_departemen !=",$query1->id_departemen)
            ->where("tipe","1")
            ->where("tanggal >=",$awal)
            ->where("tanggal <=",$akhir)
            ->countAllResults();

        $nilai3a = $this->hadir->where("nrp",$query1->nrp)
            ->where("tipe","3")
            ->where("tanggal >=",$awal)
            ->where("tanggal <=",$akhir)
            ->join("acara","hadir.kode_acara = acara.kode_acara")
            ->countAllResults();
        $nilai3b = $this->acara->where("id_departemen",$query1->id_departemen)
            ->where("tipe","3")
            ->where("tanggal >=",$awal)
            ->where("tanggal <=",$akhir)
            ->countAllResults();

        $total_hadir = $this->hadir->where("nrp",$query1->nrp)
            ->where("tipe","1")
            ->where("waktu >=",$awal)
            ->where("waktu <=",$akhir)
            ->join("acara","hadir.kode_acara = acara.kode_acara")
            ->get()
            ->getResult();

        $db = \Config\Database::connect();
        $awal_esc = $db->escape($awal);
        $akhir_esc = $db->escape($akhir);
        $first_arrivals_raw = $db->query("
            SELECT kode_acara, MIN(waktu) AS first_waktu
            FROM hadir
            WHERE waktu >= {$awal_esc} AND waktu <= {$akhir_esc}
            GROUP BY kode_acara
        ")->getResult();

        $first_arrivals = [];
        foreach ($first_arrivals_raw as $fa) {
            $first_arrivals[$fa->kode_acara] = $fa->first_waktu;
        }

        $nilai4a = 0;
        foreach($total_hadir as $h)
        {
            $first_waktu = $first_arrivals[$h->kode_acara] ?? null;
            if ($first_waktu === null) continue;

            $waktu_telat = date("H:i", strtotime('+45 minutes', strtotime($first_waktu)));
            $waktu_asli = date("H:i", strtotime($h->waktu));

            if($waktu_asli > $waktu_telat)
            {
                ++$nilai4a;
            }
        }
        $nilai4b = count($total_hadir);

        $nilai5a = $this->hadir->where("nrp",$query1->nrp)
            ->where("tipe","0")
            ->where("tanggal >=",$awal)
            ->where("tanggal <=",$akhir)
            ->join("acara","hadir.kode_acara = acara.kode_acara")
            ->countAllResults();
        $nilai5b = $this->acara->where("tipe","0")
            ->where("tanggal >=",$awal)
            ->where("tanggal <=",$akhir)
            ->countAllResults();

        $data1 = [
            "nilai_b" => $nilai1b,
        ];

        $query_indikator_1 = $this->nilai->set($data1)
            ->where("id_indikator",1)
            ->where("id_bulan",$id_bulan)
            ->where("id_pengurus",$id_pengurus)
            ->update();

        $data2 = [
            "nilai_a" => $nilai2a,
            "nilai_b" => $nilai2b,
        ];
        $query2 = $this->nilai->set($data2)
            ->where("id_indikator",2)
            ->where("id_bulan",$id_bulan)
            ->where("id_pengurus",$id_pengurus)
            ->update();

        $data3 = [
            "nilai_a" => $nilai3a,
            "nilai_b" => $nilai3b,
        ];
        $query3 = $this->nilai->set($data3)
            ->where("id_indikator",3)
            ->where("id_bulan",$id_bulan)
            ->where("id_pengurus",$id_pengurus)
            ->update();

        $data4 = [
            "nilai_a" => $nilai4a,
            "nilai_b" => $nilai4b,
        ];
        $query4 = $this->nilai->set($data4)
            ->where("id_indikator",4)
            ->where("id_bulan",$id_bulan)
            ->where("id_pengurus",$id_pengurus)
            ->update();

        $data5 = [
            "nilai_a" => $nilai5a,
            "nilai_b" => $nilai5b,
        ];
        $query5 = $this->nilai->set($data5)
            ->where("id_indikator",5)
            ->where("id_bulan",$id_bulan)
            ->where("id_pengurus",$id_pengurus)
            ->update();

        if($query_indikator_1 > 0 && $query2 > 0 && $query3 > 0 && $query4 > 0 && $query5 > 0)
        {
            return redirect()->to(base_url("admin/rapor/isi/detail/$id_pengurus"))
                ->with("berhasil","Penilaian secara auto-grading berhasil dilakukan");
        }
        return redirect()->to(base_url("admin/rapor/isi/detail/$id_pengurus"))
            ->with("error","Penilaian secara auto-grading gagal dilakukan. Ulangi lagi proses auto-grading!");
    }

    public function isi_detail($id_pengurus): string
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Isi Penilaian", "/admin/rapor/isi");
        $this->breadcrumbs->add("Detail Penilaian", "/admin/rapor/isi/detail/$id_pengurus");
        $breadcrumbs = $this->breadcrumbs->render();

        $query1 = $this->nilai->select(["nama","nilai.id_pengurus","nilai.id_indikator","nilai.id_bulan","nilai","nilai_a","nilai_b","deskripsi","nama_departemen"])
            ->where("nilai.id_pengurus",$id_pengurus)
            ->join("indikator","nilai.id_indikator = indikator.id_indikator")
            ->join("pengurus","nilai.id_pengurus = pengurus.id_pengurus")
            ->join("departemen","pengurus.id_departemen = departemen.id_departemen")
            ->join("mhs","pengurus.nrp = mhs.nrp")
            ->orderBy("pengurus.id_departemen")
            ->orderBy("nama")
            ->orderBy("nilai.id_bulan")
            ->orderBy("id_indikator")
            ->get()
            ->getResult();

        $query2 = $this->rapor->where("id_pengurus",$id_pengurus)
            ->get()
            ->getResult();

        if($query2 == null) {
            $generate_data = $this->generate_data_rapor();
        }    
        return view("admin/rapor/isi_detail",
            ["data" => $query1, "data2" => $query2, "breadcrumbs" => $breadcrumbs]);
    }

    public function isi_kirim(): RedirectResponse
    {
        $id_bulan = $this->request->getPost("id_bulan");
        $id_pengurus = $this->request->getPost("id_pengurus");
        $indikator1a = $this->request->getPost("indikator1a");
        $indikator1b = $this->request->getPost("indikator1b");
        $indikator2a = $this->request->getPost("indikator2a");
        $indikator2b = $this->request->getPost("indikator2b");
        $indikator3a = $this->request->getPost("indikator3a");
        $indikator3b = $this->request->getPost("indikator3b");
        $indikator4a = $this->request->getPost("indikator4a");
        $indikator4b = $this->request->getPost("indikator4b");
        $indikator5a = $this->request->getPost("indikator5a");
        $indikator5b = $this->request->getPost("indikator5b");
        $umpan_balik = $this->request->getPost("umpan_balik");

        // switch($indikator1a)
        // {
        //     case(0):
        //         $nilai1 = 50; break;
        //     case(1):
        //         $nilai1 = 75; break;
        //     default:
        //         $nilai1 = 100; break;
        // }
        $cek_acara_internal = $this->acara->where("acara.tipe !=","3")
            ->join("pengurus", "acara.id_departemen = pengurus.id_departemen")            
            ->where("pengurus.id_pengurus",$id_pengurus)
            ->countAllResults();
        
        switch($indikator1b)
        {
            case(0):
                $nilai1 = 100; break;
            default:
                $nilai1 = ($indikator1a === "0" && $cek_acara_internal != 0) ?  50 : min(max(ceil(($indikator1a / $indikator1b) * 100), 50 + ceil(($indikator1a / $indikator1b) * 100)), 100); break;
        }       
     
        switch($indikator2a)
        {
            case(0):
                $nilai2 = 50; break;
            case(1):
                $nilai2 = 67; break;
            case(2):
                $nilai2 = 84; break;
            default:
                $nilai2 = 100; break;
        }
        if($indikator3a === "0" && $indikator3b === "0"){
            $nilai3 = 100;
        }else{
            $nilai3 = ($indikator3b === "0" && $cek_acara_internal != 0) ?  50 : (min(ceil(($indikator3a / $indikator3b) * 50) + 50, 100));
        }
        switch($indikator4a)
        {
            case(0):
                $nilai4 = 100; break;
            case(1):
                $nilai4 = 90; break;
            case(2):
                $nilai4 = 80; break;
            case(3):
                $nilai4 = 70; break;
            case(4):
                $nilai4 = 60; break;
            default:
                $nilai4 = 50; break;
        }
        switch($indikator5a)
        {
            case(0):
                if($indikator5b === "0")
                    $nilai5 = 100;
                else
                    $nilai5 = 50; break;
            default:
                if($indikator5b === "0")
                    $nilai5 = 100;
                else
                    $nilai5 = (min(ceil(($indikator5a / $indikator5b) * 50) + 50, 100)); break;
        }

        $nilai = new Nilai();
        $data1 = [
            "nilai" => $nilai1,
            "nilai_a" => $indikator1a,
            "nilai_b" => $indikator1b,
        ];
        $query1 = $nilai->set($data1)
            ->where("id_bulan",$id_bulan)
            ->where("id_pengurus",$id_pengurus)
            ->where("id_indikator",1)
            ->update();
        $data2 = [
            "nilai" => $nilai2,
            "nilai_a" => $indikator2a,
            "nilai_b" => $indikator2b,
        ];
        $query2 = $nilai->set($data2)
            ->where("id_bulan",$id_bulan)
            ->where("id_pengurus",$id_pengurus)
            ->where("id_indikator",2)
            ->update();
        $data3 = [
            "nilai" => $nilai3,
            "nilai_a" => $indikator3a,
            "nilai_b" => $indikator3b,
        ];
        $query3 = $nilai->set($data3)
            ->where("id_bulan",$id_bulan)
            ->where("id_pengurus",$id_pengurus)
            ->where("id_indikator",3)
            ->update();
        $data4 = [
            "nilai" => $nilai4,
            "nilai_a" => $indikator4a,
            "nilai_b" => $indikator4b,
        ];
        $query4 = $nilai->set($data4)
            ->where("id_bulan",$id_bulan)
            ->where("id_pengurus",$id_pengurus)
            ->where("id_indikator",4)
            ->update();
        $data5 = [
            "nilai" => $nilai5,
            "nilai_a" => $indikator5a,
            "nilai_b" => $indikator5b,
        ];
        $query5 = $nilai->set($data5)
            ->where("id_bulan",$id_bulan)
            ->where("id_pengurus",$id_pengurus)
            ->where("id_indikator",5)
            ->update();

        $rapor = new Rapor();
        $query6 = $rapor->set(["umpan_balik" => $umpan_balik])
            ->where("id_pengurus",$id_pengurus)
            ->where("id_bulan",$id_bulan)
            ->update();

        if($query1 > 0 && $query2 > 0 && $query3 > 0 && $query4 > 0 && $query5 > 0 && $query6 > 0)
        {
            return redirect()->to(base_url("admin/rapor/isi"))
                ->with("berhasil","Pengisian nilai rapor berhasil disimpan");
        }
        return redirect()->to(base_url("admin/rapor/isi"))
            ->with("error","Pengisian nilai rapor gagal disimpan");
    }

    public function hasil()
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Hasil Rapor", "/admin/rapor/hasil");
        $breadcrumbs = $this->breadcrumbs->render();

        $query1 = $this->nilai->where("nilai.id_pengurus",$this->id_pengurus)
            ->where("nilai <>",0)
            ->get()
            ->getResult();

        $query2 = $this->rapor->where("id_pengurus",$this->id_pengurus)
            ->get()
            ->getResult();

        switch(array_key_last($query1))
        {
            case(4):case(5):case(6):case(7):case(8):
            $query3 = $this->nilai->select(["nama","nilai.id_pengurus","nilai.id_indikator","nilai.id_bulan","nilai","deskripsi","nama_departemen","jabatan"])
                ->where("nilai.id_pengurus",$this->id_pengurus)
                ->where("nilai.id_bulan",2)
                ->join("indikator","nilai.id_indikator = indikator.id_indikator")
                ->join("pengurus","nilai.id_pengurus = pengurus.id_pengurus")
                ->join("departemen","pengurus.id_departemen = departemen.id_departemen")
                ->join("mhs","pengurus.nrp = mhs.nrp")
                ->orderBy("pengurus.id_departemen")
                ->orderBy("nama")
                ->orderBy("nilai.id_bulan")
                ->orderBy("id_indikator")
                ->get()
                ->getResult();
            return view("admin/rapor/hasil",["data" => $query3, "data2" => $query2, "breadcrumbs" => $breadcrumbs]);
            case(9):case(10):case(11):case(12):case(13):
            $query3 = $this->nilai->select(["nama","nilai.id_pengurus","nilai.id_indikator","nilai.id_bulan","nilai","deskripsi","nama_departemen","jabatan"])
                ->where("nilai.id_pengurus",$this->id_pengurus)
                ->where("nilai.id_bulan <>",3)
                ->join("indikator","nilai.id_indikator = indikator.id_indikator")
                ->join("pengurus","nilai.id_pengurus = pengurus.id_pengurus")
                ->join("departemen","pengurus.id_departemen = departemen.id_departemen")
                ->join("mhs","pengurus.nrp = mhs.nrp")
                ->orderBy("pengurus.id_departemen")
                ->orderBy("nama")
                ->orderBy("nilai.id_bulan")
                ->orderBy("id_indikator")
                ->get()
                ->getResult();
            return view("admin/rapor/hasil",
                ["data" => $query3, "data2" => $query2, "breadcrumbs" => $breadcrumbs]);
            case(14):
                $query3 = $this->nilai->select(["nama","nilai.id_pengurus","nilai.id_indikator","nilai.id_bulan","nilai","deskripsi","nama_departemen","jabatan"])
                    ->where("nilai.id_pengurus",$this->id_pengurus)
                    ->join("indikator","nilai.id_indikator = indikator.id_indikator")
                    ->join("pengurus","nilai.id_pengurus = pengurus.id_pengurus")
                    ->join("departemen","pengurus.id_departemen = departemen.id_departemen")
                    ->join("mhs","pengurus.nrp = mhs.nrp")
                    ->orderBy("pengurus.id_departemen")
                    ->orderBy("nama")
                    ->orderBy("nilai.id_bulan")
                    ->orderBy("id_indikator")
                    ->get()
                    ->getResult();
                return view("admin/rapor/hasil",
                    ["data" => $query3, "data2" => $query2, "breadcrumbs" => $breadcrumbs]);
            default:
                break;
        }
        return redirect()->to(base_url("admin/beranda"))
            ->with("error","Maaf, Rapor Fungsionaris milikmu belum siap. Hubungi Kepala Departemen untuk <b>simpan permanen</b> nilai!");
    }

    public function hasil_post(): string
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Daftar Rapor", "/admin/rapor/dashboard");
        $this->breadcrumbs->add("Hasil Rapor", "/admin/rapor/hasil");
        $breadcrumbs = $this->breadcrumbs->render();

        $id_pengurus = $this->request->getPost("id_pengurus");
        $id_bulan = $this->request->getPost("id_bulan");

        $query1 = $this->rapor->where("id_pengurus",$id_pengurus)
            ->where("id_bulan",$id_bulan)
            ->get()
            ->getResult();
        $query2 = $this->nilai->select(["nama","nilai.id_pengurus","nilai.id_indikator","nilai.id_bulan","nilai","deskripsi","nama_departemen","jabatan"])
            ->where("nilai.id_pengurus",$id_pengurus)
            ->where("nilai.id_bulan",$id_bulan)
            ->join("indikator","nilai.id_indikator = indikator.id_indikator")
            ->join("pengurus","nilai.id_pengurus = pengurus.id_pengurus")
            ->join("departemen","pengurus.id_departemen = departemen.id_departemen")
            ->join("mhs","pengurus.nrp = mhs.nrp")
            ->orderBy("pengurus.id_departemen")
            ->orderBy("nama")
            ->orderBy("nilai.id_bulan")
            ->orderBy("id_indikator")
            ->get()
            ->getResult();
        return view("admin/rapor/hasil",
            ["data" => $query2, "data2" => $query1, "breadcrumbs" => $breadcrumbs]);
    }



}
