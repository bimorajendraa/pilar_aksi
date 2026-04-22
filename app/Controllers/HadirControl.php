<?php

namespace App\Controllers;

use App\Models\Acara;
use App\Models\Hadir;
use App\Models\Pengurus;
use App\Modules\Breadcrumbs\Breadcrumbs;
use CodeIgniter\HTTP\RedirectResponse;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelMedium;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class HadirControl extends BaseController
{
    public Breadcrumbs $breadcrumbs;
    public Pengurus $pengurus;
    public Acara $acara;
    public Hadir $hadir;
    public int $id_pengurus;
    public array $form;

    public function __construct()
    {
        $this->breadcrumbs = new Breadcrumbs();
        $this->pengurus = new Pengurus();
        $this->acara = new Acara();
        $this->hadir = new Hadir();
        $this->id_pengurus = session("id_pengurus");
    }

    public function index(): string
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Daftar Acara", "/admin/hadir/dashboard");
        $breadcrumbs = $this->breadcrumbs->render();

        $query1 = $this->pengurus->where("id_pengurus",$this->id_pengurus)
            ->first();

        if($this->id_pengurus < 20000)
        {
            $db = \Config\Database::connect();
            $query2 = $db->query("
                SELECT a.kode_acara, a.nama_acara, a.tanggal, d.nama_departemen, a.lokasi, m.nama, p.jabatan, a.status,
                       COALESCE(hc.jumlah, 0) AS jumlah
                FROM acara a
                LEFT JOIN (SELECT kode_acara, COUNT(*) AS jumlah FROM hadir GROUP BY kode_acara) hc ON hc.kode_acara = a.kode_acara
                JOIN pengurus p ON a.pembuat = p.id_pengurus
                JOIN mhs m ON p.nrp = m.nrp
                JOIN departemen d ON p.id_departemen = d.id_departemen
                ORDER BY a.tanggal DESC
            ")->getResult();

            return view("admin/hadir/index",
                ["data" => $query2, "breadcrumbs" => $breadcrumbs]);
        }
        $db = \Config\Database::connect();
        $id_dept = $db->escape($query1->id_departemen);
        $query3 = $db->query("
            SELECT a.kode_acara, a.nama_acara, a.tanggal, d.nama_departemen, a.lokasi, m.nama, p.jabatan, a.status,
                   COALESCE(hc.jumlah, 0) AS jumlah
            FROM acara a
            LEFT JOIN (SELECT kode_acara, COUNT(*) AS jumlah FROM hadir GROUP BY kode_acara) hc ON hc.kode_acara = a.kode_acara
            JOIN pengurus p ON a.pembuat = p.id_pengurus
            JOIN mhs m ON p.nrp = m.nrp
            JOIN departemen d ON p.id_departemen = d.id_departemen
            WHERE a.id_departemen = {$id_dept}
            ORDER BY a.tanggal DESC
        ")->getResult();
        return view("admin/hadir/index",
            ["data" => $query3, "breadcrumbs" => $breadcrumbs]);
    }

    public function tambah(): string
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Daftar Acara", "/admin/hadir/dashboard");
        $this->breadcrumbs->add("Buat Tautan Baru", "/admin/hadir/tambah");
        $breadcrumbs = $this->breadcrumbs->render();

        return view("admin/hadir/tambah",
            ["breadcrumbs" => $breadcrumbs]);
    }

    public function tambah_kirim(): RedirectResponse
    {
        $this->ambil_form();
        [, $nama_acara, $tanggal, $lokasi, $narahubung1, $no_wa1, $id_line1, $tipe, $id_departemen] = $this->form;

        switch($this->cek_form($no_wa1, $id_line1, $lokasi))
        {
            case(1):
                return redirect()->to(base_url("admin/hadir/tambah"))
                    ->with("error","Lokasi acara <b>TIDAK VALID</b>. Acara daring CUKUP ditulis <b>link online meeting-nya saja</b>.");
            case(2):
                return redirect()->to(base_url("admin/hadir/tambah"))
                    ->with("error","Lokasi acara <b>TIDAK VALID</b>. Acara daring WAJIB didahului <b>https://</b> pada awal link.");
            case(3):
                return redirect()->to(base_url("admin/hadir/tambah"))
                    ->with("error","Lokasi acara <b>TIDAK VALID</b>. Acara daring WAJIB ditulis <b>link online meeting-nya</b>.");
            case(4):
                return redirect()->to(base_url("admin/hadir/tambah"))
                    ->with("error","Lokasi acara <b>TIDAK VALID</b>. Acara luring WAJIB ditulis <b>nama lokasi / gedungnya</b>.");
            case(5):
                return redirect()->to(base_url("admin/hadir/tambah"))
                    ->with("error","Data Narahubung belum diisi. Silakan melengkapi datanya terlebih dahulu.");
            default:
                break;
        }

        $query2 = $this->acara->where("id_departemen",$id_departemen)
            ->orderBy("kode_acara","DESC")
            ->first();

        if($query2 == null)
            $nomor = 0;
        else
            $nomor = (int)substr($query2->kode_acara, 2, 2);
        $kode_acara = (($id_departemen <= 9) ? "0" . $id_departemen : $id_departemen) . (($nomor + 1 <= 9) ? "0" . ($nomor + 1) : ($nomor + 1));

        $data = [
            "kode_acara" => $kode_acara,
            "nama_acara" => $nama_acara,
            "id_departemen" => $id_departemen,
            "tanggal" => $tanggal,
            "lokasi" => $lokasi,
            "pembuat" => $this->id_pengurus,
            "narahubung" => $narahubung1,
            "tipe" => $tipe
        ];
        $query3 = $this->acara->insert($data);

        if($query3 > 0)
        {
            $query4 = $this->acara->select("kode_acara")
                ->where("id_acara",$query3)
                ->first();

            return redirect()->to(base_url("admin/hadir/detail/$query4->kode_acara"))
                ->with("berhasil","Tautan acara baru berhasil dibuat");
        }
        return redirect()->to(base_url("admin/hadir/tambah"))
            ->with("error","Data gagal disimpan ke Database");
    }

    public function detail($kode_acara): string
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Daftar Acara", "/admin/hadir/dashboard");
        $this->breadcrumbs->add("Detail Acara", "/admin/hadir/detail/$kode_acara");
        $breadcrumbs = $this->breadcrumbs->render();

        $db = \Config\Database::connect();
        $kode_escaped = $db->escape($kode_acara);
        $query1 = null;
        try {
            $result = $db->query("
                SELECT a.*, COALESCE(hc.jumlah, 0) AS jumlah
                FROM acara a
                LEFT JOIN (SELECT kode_acara, COUNT(*) AS jumlah FROM hadir GROUP BY kode_acara) hc ON hc.kode_acara = a.kode_acara
                WHERE a.kode_acara = {$kode_escaped}
                LIMIT 1
            ");
            if ($result !== false) {
                $query1 = $result->getRow();
            }
        } catch (\Throwable $e) {
            $query1 = null;
        }

        if ($query1 === null) {
            $query1 = $this->acara->where("kode_acara", $kode_acara)->first();
            if ($query1 === null) {
                return view("errors/404");
            }
            $query1->jumlah = 0;
        }

        if (!isset($query1->jumlah)) {
            $query1->jumlah = 0;
        }

        $query2 = $this->pengurus->select(["mhs.nama", "mhs.nrp"])
            ->where("id_pengurus", $query1->narahubung)
            ->join("mhs", "pengurus.nrp = mhs.nrp", "left")
            ->first();

        if ($query2 === null) {
            $query2 = (object) [
                "nama" => "Narahubung tidak ditemukan",
                "nrp" => "-"
            ];
        }

        $hasil_url = "";
        $qr_cache_path = WRITEPATH . "cache/qr_{$kode_acara}.png";
        $qr_binary = file_exists($qr_cache_path) ? (string) @file_get_contents($qr_cache_path) : "";

        if ($qr_binary === "") {
            try {
                $writer = new PngWriter();
                $qr_code = QrCode::create(base_url("/$query1->kode_acara"))
                    ->setEncoding(new Encoding('UTF-8'))
                    ->setErrorCorrectionLevel(new ErrorCorrectionLevelMedium())
                    ->setSize(500)
                    ->setMargin(20)
                    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                    ->setForegroundColor(new Color(0, 0, 0))
                    ->setBackgroundColor(new Color(255, 255, 255));

                $logo_path = FCPATH . "pic/pilar-aksi-logo.png";
                if (is_file($logo_path)) {
                    $logo = Logo::create($logo_path)->setResizeToWidth(100);
                    $hasil = $writer->write($qr_code, $logo);
                } else {
                    $hasil = $writer->write($qr_code);
                }

                $qr_binary = $hasil->getString();
                @file_put_contents($qr_cache_path, $qr_binary);
            } catch (\Throwable $e) {
                $qr_binary = "";
            }
        }

        if ($qr_binary !== "") {
            $hasil_url = "data:image/png;base64," . base64_encode($qr_binary);
        }

        return view("admin/hadir/detail",
            ["data" => $query1, "data2" => $query2, "data3" => $hasil_url, "breadcrumbs" => $breadcrumbs]);
    }

    public function ubah($kode_acara): string
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Daftar Acara", "/admin/hadir/dashboard");
        $this->breadcrumbs->add("Detail Acara", "/admin/hadir/detail/$kode_acara");
        $this->breadcrumbs->add("Ubah Acara", "/admin/hadir/ubah/$kode_acara");
        $breadcrumbs = $this->breadcrumbs->render();

        $query1 = $this->acara->where("kode_acara", $kode_acara)
            ->join("pengurus","acara.narahubung = pengurus.id_pengurus")
            ->join("mhs","pengurus.nrp = mhs.nrp")
            ->first();

        return view("admin/hadir/ubah",["data" => $query1, "breadcrumbs" => $breadcrumbs]);
    }

    public function ubah_kirim(): RedirectResponse
    {
        $this->ambil_form();
        [$kode_acara, $nama_acara, $tanggal, $lokasi, $narahubung1, $no_wa1, $id_line1, $tipe, $id_departemen] = $this->form;

        switch($this->cek_form($no_wa1, $id_line1, $lokasi))
        {
            case(1):
                return redirect()->to(base_url("admin/hadir/ubah/$kode_acara"))
                    ->with("error","Lokasi acara <b>TIDAK VALID</b>. Acara daring CUKUP ditulis <b>link online meeting-nya saja</b>.");
            case(2):
                return redirect()->to(base_url("admin/hadir/ubah/$kode_acara"))
                    ->with("error","Lokasi acara <b>TIDAK VALID</b>. Acara daring WAJIB didahului <b>https://</b> pada awal link.");
            case(3):
                return redirect()->to(base_url("admin/hadir/ubah/$kode_acara"))
                    ->with("error","Lokasi acara <b>TIDAK VALID</b>. Acara daring WAJIB ditulis <b>link online meeting-nya</b>.");
            case(4):
                return redirect()->to(base_url("admin/hadir/ubah/$kode_acara"))
                    ->with("error","Lokasi acara <b>TIDAK VALID</b>. Acara luring WAJIB ditulis <b>nama lokasi / gedungnya</b>.");
            case(5):
                return redirect()->to(base_url("admin/hadir/ubah/$kode_acara"))
                    ->with("error","Data Narahubung belum diisi. Silakan melengkapi datanya terlebih dahulu.");
            default:
                break;
        }

        $data = [
            "nama_acara" => $nama_acara,
            "id_departemen" => $id_departemen,
            "tanggal" => $tanggal,
            "lokasi" => $lokasi,
            "pembuat" => $this->id_pengurus,
            "narahubung" => $narahubung1,
            "tipe" => $tipe
        ];
        $query2 = $this->acara->set($data)
            ->where("kode_acara",$kode_acara)
            ->update();

        if($query2 > 0)
        {
            return redirect()->to(base_url("admin/hadir/dashboard"))
                ->with("berhasil","Data berhasil diperbarui");
        }
        return redirect()->to(base_url("admin/hadir/ubah/$kode_acara"))
            ->with("error","Data gagal disimpan ke Database");
    }

    public function rekap(): string
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Rekap Kehadiran", "/admin/hadir/rekap");
        $breadcrumbs = $this->breadcrumbs->render();

        $query1 = $this->pengurus->where("id_pengurus",$this->id_pengurus)
            ->first();

        if($this->id_pengurus < 20000)
        {
            $query2 = $this->acara->select(["nama","jabatan","nama_departemen","nama_acara","acara.kode_acara","tanggal","lokasi","COUNT(hadir.kode_acara) as peserta"])
                ->join("hadir","acara.kode_acara = hadir.kode_acara")
                ->join("pengurus","acara.pembuat = pengurus.id_pengurus")
                ->join("mhs","pengurus.nrp = mhs.nrp")
                ->join("departemen","pengurus.id_departemen = departemen.id_departemen")
                ->groupBy("acara.kode_acara")
                ->orderBy("tanggal","desc")
                ->get()
                ->getResult();
            return view("admin/hadir/rekap",
                ["data" => $query2, "breadcrumbs" => $breadcrumbs]);
        }
        $query3 = $this->acara->select(["nama","jabatan","nama_departemen","nama_acara","acara.kode_acara","tanggal","lokasi","COUNT(hadir.kode_acara) as peserta"])
            ->where("acara.id_departemen",$query1->id_departemen)
            ->join("hadir","acara.kode_acara = hadir.kode_acara")
            ->join("pengurus","acara.pembuat = pengurus.id_pengurus")
            ->join("mhs","pengurus.nrp = mhs.nrp")
            ->join("departemen","pengurus.id_departemen = departemen.id_departemen")
            ->groupBy("acara.kode_acara")
            ->orderBy("tanggal","desc")
            ->get()
            ->getResult();
        return view("admin/hadir/rekap",
            ["data" => $query3, "breadcrumbs" => $breadcrumbs]);
    }

    public function rekap_detail(): string
    {
        $this->breadcrumbs->add("Beranda", "/admin/beranda");
        $this->breadcrumbs->add("Rekap Kehadiran", "/admin/hadir/rekap");
        $this->breadcrumbs->add("Detail Laporan", "/admin/hadir/rekap/detail");
        $breadcrumbs = $this->breadcrumbs->render();

        $kode_acara = $this->request->getPost("kode_acara");

        $query1 = $this->hadir->select(["mhs.nama","mhs.nrp","waktu","departemen.nama_departemen","pengurus.jabatan","hadir.keterangan"])
            ->where("kode_acara", $kode_acara)
            ->join("mhs","hadir.nrp = mhs.nrp")
            ->join("pengurus","hadir.nrp = pengurus.nrp","left outer")
            ->join("departemen","pengurus.id_departemen = departemen.id_departemen","left outer")
            ->get()
            ->getResult();

        $query2 = $this->acara->where("kode_acara", $kode_acara)
            ->first();

        $query3 = $this->pengurus->select(["nama","mhs.nrp"])
            ->where("id_pengurus",$this->id_pengurus)
            ->join("mhs","pengurus.nrp = mhs.nrp")
            ->first();

        return view("admin/hadir/rekap_detail",
            ["data" => $query1, "data1" => $query2, "data2" => $query3, "breadcrumbs" => $breadcrumbs]);
    }

    public function hapus($kode_acara): RedirectResponse
    {
        $query1 = $this->hadir->where("kode_acara",$kode_acara)
            ->countAllResults();

        if($query1 === 0)
        {
            $this->acara->where("kode_acara", $kode_acara)
                ->delete();
            return redirect()->to(base_url("admin/hadir/dashboard"))
                ->with('berhasil',"Acara berhasil dibatalkan");
        }
        return redirect()->to(base_url("admin/hadir/dashboard"))
            ->with("error","Maaf, acara ini sedang berjalan sehingga tidak dapat dihapus");
    }

    public function tutup($kode_acara): RedirectResponse
    {
        $this->acara->set(["status" => 1])
            ->where("kode_acara", $kode_acara)
            ->update();
        return redirect()->to(base_url("admin/hadir/dashboard"))
            ->with("berhasil","Akses Acara berhasil ditutup");
    }

    public function buka($kode_acara): RedirectResponse
    {
        $this->acara->set(["status" => 0])
            ->where("kode_acara", $kode_acara)
            ->update();
        return redirect()->to(base_url("admin/hadir/dashboard"))
            ->with("berhasil","Akses Acara berhasil dibuka kembali");
    }

    public function ambil_form(): void
    {
        $kode_acara = $this->request->getPost("kode_acara");
        $nama_acara = $this->request->getPost("nama_acara");
        $tanggal = $this->request->getPost("tanggal");
        $lokasi = $this->request->getPost("lokasi");
        $narahubung1 = $this->request->getPost("narahubung1");
        $no_wa1 = $this->request->getPost("no_wa1");
        $id_line1 = $this->request->getPost("id_line1");
        $tipe = $this->request->getPost("tipe");

        $query1 = $this->pengurus->where("id_pengurus",$this->id_pengurus)->first();
        $id_departemen = $query1->id_departemen;

        $this->form = array($kode_acara, $nama_acara, $tanggal, $lokasi, $narahubung1, $no_wa1, $id_line1, $tipe, $id_departemen);
    }

    public function cek_form($no_wa1, $id_line1, $lokasi): int
    {
        $cek_lokasi = explode(" ",$lokasi);
        $cek_panjang = array_key_last($cek_lokasi);

        foreach($cek_lokasi as $c)
        {
            if($cek_panjang > 0 && (!filter_var($c, FILTER_VALIDATE_URL) === false))
            {
                return 1;
            }

            if(
                strpos($c, "intip.in") === 0 ||
                strpos($c, "tekan.id") === 0 ||
                strpos($c, "zoom.us") === 0 ||
                strpos($c, "bit.ly") === 0 ||
                strpos($c, "its.id") === 0 ||
                strpos($c, "s.id") === 0
            ){
                return 2;
            }

            if(
                strtolower($c) === "online" ||
                strtolower($c) === "daring" ||
                strtolower($c) === "zoom" ||
                strtolower($c) === "meeting" ||
                strtolower($c) === "teams" ||
                strtolower($c) === "google" ||
                strtolower($c) === "meet" ||
                strtolower($c) === "melalui" ||
                strtolower($c) === "via"
            ){
                return 3;
            }

            if(
                strtolower($c) === "offline" ||
                strtolower($c) === "luring"
            ){
                return 4;
            }

            if($no_wa1 === "" || $id_line1 === "")
            {
                return 5;
            }
        }
        return 0;
    }
}
