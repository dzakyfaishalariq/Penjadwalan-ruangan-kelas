<?php
namespace App\Http\Controllers;

use App\Services\mahasiswaService;
use Illuminate\Http\Request;

class mahassiwaController extends Controller
{
    // inisial class service mahasiswa
    public $mahasiswaService;

    public function __construct(mahasiswaService $mahasiswaService)
    {
        $this->mahasiswaService = $mahasiswaService;
    }

    public function getMahasiswa($paginate, Request $request)
    {
        // memanggil fungsi getMahasiswa untuk menampilkan semua data mahasiswa pada class mahasiswaService
        $respons = $this->mahasiswaService->getMahasiswa($paginate, $request);

        return $respons;
    }

    public function getMahasiswaById($id)
    {
        // memanggil fungsi getMahasiswaById untuk menampilkan data mahasiswa berdasarkan id pada class mahasiswaService
        $respons = $this->mahasiswaService->getMahasiswaById($id);

        return $respons;
    }

    public function createMahasiswa(Request $request)
    {
        // memanggil fungsi createMahasiswa untuk menambahkan data mahasiswa pada class mahasiswaService
        $respons = $this->mahasiswaService->createMahasiswa($request);

        return $respons;
    }

    public function verifyMahasiswa(Request $request)
    {
        // memanggil fungsi verifyMahasiswa untuk memverifikasi data mahasiswa pada class mahasiswaService
        $respons = $this->mahasiswaService->verifyMahasiswa($request);

        return $respons;
    }

    public function updateMahasiswa(Request $request, $id)
    {
        // memanggil fungsi updateMahasiswa untuk mengupdate data mahasiswa pada class mahasiswaService
        $respons = $this->mahasiswaService->updateMahasiswa($request, $id);

        return $respons;
    }

    public function deleteMahasiswa($id)
    {
        // memanggil fungsi deleteMahasiswa untuk menghapus data mahasiswa pada class mahasiswaService
        $respons = $this->mahasiswaService->deleteMahasiswa($id);

        return $respons;
    }

    public function totalMahasiswa()
    {
        // memanggil fungsi totalMahasiswa untuk menampilkan jumlah data mahasiswa pada class mahasiswaService
        $respons = $this->mahasiswaService->totalMahasiswa();

        return $respons;
    }
}
