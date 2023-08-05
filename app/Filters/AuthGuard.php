<?php 
namespace App\Filters;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthGuard implements FilterInterface
{

  protected $hakAksesControllers = [
    'Home' => ['admin', 'karyawan'],
    'Menu' => ['admin'],
    'Karyawan' => ['admin'],
    'Pesanan' => ['admin', 'karyawan'],
    'LabelPesanan' => ['admin', 'karyawan'],
    'Pengeluaran' => ['admin'],
    'LabelPengeluaran' => ['admin'],
    'Laporan' => ['admin']
  ];

  public function before(RequestInterface $request, $arguments = null)
  {
    $session = session();
    if (!$session->get('login'))
    {
      return redirect()
          ->to(base_url('/login'));
    }

    $namaController = class_basename(service('router')->controllerName()); 
      
    $level = $session->get('level');
    if (isset($this->hakAksesControllers[$namaController])) {
      $hakAksesController = $this->hakAksesControllers[$namaController];
      if (!in_array($level, $hakAksesController)) {
        $data['menus'] = config('App')->menus;
        $data['menuAktif'] = null;
        return \Config\Services::response()->setBody(view("Forbidden/forbidden.php", $data));
      }
    }


  }
  
  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
      
  }
}