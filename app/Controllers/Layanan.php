<?php
/**
*	App Name	: Aplikasi Antrian Professional	
*	Developed by: Agus Prawoto Hadi
*	Website		: https://jagowebdev.com
*	Year		: 2022
*/

namespace App\Controllers;
use App\Models\LayarModel;

class Layanan extends \App\Controllers\BaseController
{
	public function __construct() {
		
		parent::__construct();
		
		$this->model = new LayarModel;	
		$this->data['site_title'] = 'Antrian';
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/layar-ambil-antrian.js' );
		$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/layar-monitor.js' );
	}
	
	public function index()
	{
		$this->hasPermissionPrefix('read');
		if (!empty($_POST['delete'])) 
		{
			$this->hasPermission('delete_all', true);
			$result = $this->model->deleteData();
			
			if ($result) {
				$this->data['message'] = ['status' => 'ok', 'message' => 'Data tujuan berhasil dihapus'];
			} else {
				$this->data['message'] = ['status' => 'error', 'message' => 'Data tujuan gagal dihapus'];
			}
		}
		
		$this->data['antrian_kategori'] = $this->model->getAllSettingLayar();
		$this->view('layar-antrian-result.php', $this->data);
	}
	
	public function antrian() {
		$this->hasPermissionPrefix('read');
		
		$user = $this->session->get('user');
		
		// $this->setKategori();
		if (!empty($_GET['id'])) {
			$this->show_layar_antrian();
		} else {
			if (!empty($_POST['delete'])) 
		{
			$this->hasPermission('delete_all', true);
			$result = $this->model->deleteData();
			
			if ($result) {
				$this->data['message'] = ['status' => 'ok', 'message' => 'Data tujuan berhasil dihapus'];
			} else {
				$this->data['message'] = ['status' => 'error', 'message' => 'Data tujuan gagal dihapus'];
			}
		}
			$this->data['setting_layar'] = $this->model->getAllSettingLayar();
			$data = $this->model->getAllTujuan();
	        $kategori = $this->model->getKategoriByUserId($this->session->get('user')['id_user']);
	        $layanan  = $this->model->getLayananByUserId($this->session->get('user')['id_user']);
			foreach ($data as $val) {
				$tujuan[$val['id_setting_layar']][] = $val['nama_antrian_tujuan'];
			}
			$this->addStyle ("https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css");
			$this->addJs("https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js");
			$this->data['tujuan'] = $tujuan;
			$this->data['kategori'] = $kategori;
			$this->data['layanan'] = $layanan;
			$this->view('layar-layanan-result.php', $this->data);
		}
	}

	public function add() 
	{
		
		if (!empty($_POST['id'])) {
			$this->setEdit();
		} else {
			$this->addStyle ("https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css");
			$this->addJs("https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js");
			$kategori = $this->model->getKategoriByUserId($this->session->get('user')['id_user']);
			$this->data['kategori'] = $kategori;
			$this->data['breadcrumb']['Add'] = '';
			$this->data['title'] = 'Tambah Data Layanan';
		}

		$data['message'] = [];
		if (isset($_POST['submit'])) 
		{
			$this->saveData();
			if ($this->data['message']['status'] == 'ok') {
				$this->setEdit();
				$this->data['result'] = $this->model->getLayananById($this->data['id']);
			}
		}
		
		$this->view('layar-layanan-form.php', $this->data);
	}

	public function edit()
	{
		$this->hasPermissionPrefix('update');
	
		$this->addStyle ("https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.css");
		$this->addJs("https://cdn.ckeditor.com/ckeditor5/44.1.0/ckeditor5.umd.js");
		$kategori = $this->model->getKategoriByUserId($this->session->get('user')['id_user']);
		$this->data['kategori'] = $kategori;
			$this->data['breadcrumb']['Add'] = '';
			$this->data['title'] = 'Tambah Data Layanan';
		
		if (empty($_GET['id'])) {
			$this->errorDataNotFound();
		}
		
		$id = !empty($_POST['id']) ? $_POST['id'] : $_GET['id'];
		
		// Submit
		$this->data['message'] = [];
		if (isset($_POST['submit'])) 
		{
			$this->saveData();
		}
		
		$this->data['breadcrumb']['Edit'] = '';
		$this->data['id'] = $id;
		$this->data['result'] = $this->model->getLayananById($id);
		if (empty($this->data['result'])) {
			$this->errorDataNotFound();
		}
		
		$this->view('layar-layanan-form.php', $this->data);
	}
	
	private function setKategori() {
		
		$antrian_kategori = $this->model->getAntrianKategoriAktif();
		if (count($antrian_kategori) == 1) {
			$_GET['id'] = $antrian_kategori[0]['id_antrian_kategori'];
		}
	}
	
	public function show_layar_antrian() 
	{
		if (empty($_GET['id'])) {
			$this->errorDataNotFound();
		}
		
		$id = $_GET['id'];
		if($id == 'all'){
			$antrian_detail = $this->model->getTujuanByAllLayarSetting();
			if (!$antrian_detail) {
				$this->errorDataNotFound();
			}
			$this->data['identitas'] = $this->model->getIdentitas();
			$query = $this->model->getSettingLayarMonitor();
			foreach ($query as $val) {
				$param[$val['param']] = $val['value'];
			}
			$this->data['setting'] = $param;
				
			// $this->data['antrian_detail'] = $antrian_detail;
			$this->data['antrian_kategori'] = $this->model->getAntrianKategoriByAllLayar();
			// $this->data['urut'] = $this->model->getAntrianUrut($id);
			
			echo view('themes/modern/layar-antrian-show.php', $this->data);
		}else{
			$antrian_detail = $this->model->getTujuanByIdLayarSetting($id);
			if (!$antrian_detail) {
				$this->errorDataNotFound();
			}
			$this->data['identitas'] = $this->model->getIdentitas();
			$query = $this->model->getSettingLayarMonitor();
			foreach ($query as $val) {
				$param[$val['param']] = $val['value'];
			}
			$this->data['setting'] = $param;
				
			// $this->data['antrian_detail'] = $antrian_detail;
			$this->data['antrian_kategori'] = $this->model->getAntrianKategoriByIdLayar($id);
			// $this->data['urut'] = $this->model->getAntrianUrut($id);
			
			echo view('themes/modern/layar-antrian-show.php', $this->data);
		}
		
	}
	
	public function ajaxGetFormPilihPrinter() {
		$this->data['setting_layar'] = $this->model->getSettingLayarById($_GET['id']);
		$this->data['setting_printer'] = $this->model->getSettingPrinter();
		echo view('themes/modern/layar-pilih-printer.php', $this->data);
	}
	
	public function ajaxUpdateDataPilihPrinter() {
		
		$result = $this->model->saveDataPilihPrinter();
		if ($result) {
			
			$message = ['status' => 'ok'
						, 'message' => 'Data berhasil disimpan'
						, 'printer' => $this->model->getSettingPrinterById($_POST['id_setting_printer'])
					];
		} else {
			$message = ['status' => 'error', 'message' => 'Data gagal disimpan'];
		}
		echo json_encode($message);
	}
	
	public function show_layar_monitor() 
	{
		$this->data['title'] = 'Layar Monitor Antrian';
		$this->setKategori();

		if (!empty($_GET['id'])) 
		{
			$id = $_GET['id'];
			$result = $this->model->getTujuanByIdLayarSetting($id);
			
			if (!$result) {
				$this->printError('Layar Antrian dengan id ' . $_GET['id'] . ' Tidak Ditemukan');
				return;
			}
			$this->addJs ( $this->config->baseURL . 'public/vendors/plyr/plyr.js' );
			$this->addJs ( $this->config->baseURL . 'public/themes/modern/js/layar-monitor-show.js' );
			$this->addJs( $this->config->baseURL . 'public/themes/modern/js/layar-liveupdate-monitor-antrian.js' );
			$this->addStyle ( $this->config->baseURL . 'public/themes/modern/css/layar-monitor-show.css' );
			$this->addStyle ( $this->config->baseURL . 'public/themes/modern/css/layar-monitor-show-font.css' );
			$this->addStyle ( $this->config->baseURL . 'public/vendors/plyr/plyr.css' );		
			
			foreach ($result as $val) {
				$antrian_detail[$val['id_antrian_detail']] = $val;
			}
			 // echo '<pre>'; print_r($antrian_detail); die;
			$this->data['identitas'] = $this->model->getIdentitas();
			$this->data['antrian_detail'] = $antrian_detail;
			$this->data['antrian_terakhir'] = $this->model->getAntrianDipanggilTerakhir($id);
			$this->data['awalan_panggil'] = $this->model->getAwalanPanggil();
			
			// Urut
			$query = $this->model->getAntrianDipanggilByTujuan($id);
			$urut = [];
			// echo '<pre>'; print_r($query); die;
			foreach ($query as $val) {
				$urut[$val['id_antrian_detail']] = $val;
			}
			// echo '<pre>'; print_r($urut); die;
			$this->data['urut'] = $urut;
			
			$query = $this->model->getSettingLayarMonitor();
			foreach ($query as $val) {
				$param[$val['param']] = $val['value'];
			}
			$this->data['setting'] = $param;
			
			echo view('themes/modern/layar-monitor-show.php', $this->data);
		} else {
			$this->data['setting_layar'] = $this->model->getAllSettingLayar();
			$data = $this->model->getAllTujuan();
			foreach ($data as $val) {
				$tujuan[$val['id_setting_layar']][] = $val['nama_antrian_tujuan'];
			}
			$this->data['tujuan'] = $tujuan;
			
			// $this->data['antrian'] = $this->model->getAntrianKategori();
			$this->view('layar-monitor-kategori.php', $this->data);
		}
	}

	/**
	 * detail layanan
	 */

	public function show_layar_antrian_detail(){
		$id = $_GET['id'];
		$this->data['layanan'] = $this->model->getAntrianKategoriById($id);
		$this->data['daftar_layanan'] = $this->model->getAntrianLayananByIdKategori($id);
		$this->data['title'] = 'Jenis Layanan';
		$query = $this->model->getSettingLayarMonitor();
			foreach ($query as $val) {
				$param[$val['param']] = $val['value'];
			}
			$this->data['setting'] = $param;
			$this->data['identitas'] = $this->model->getIdentitas();
		    echo view('themes/modern/layar-antrian-show-layanan.php', $this->data);
	}
	public function show_layar_antrian_detail_layanan(){
		$id = $_GET['id'];
		$this->data['detail_layanan'] = $this->model->getDetailLayananByIdKategori($id);
		$this->data['title'] = 'Jenis Layanan';
		$query = $this->model->getSettingLayarMonitor();
			foreach ($query as $val) {
				$param[$val['param']] = $val['value'];
			}
			$this->data['setting'] = $param;
			$this->data['identitas'] = $this->model->getIdentitas();
		    echo view('themes/modern/layar-antrian-show-layanan-detail.php', $this->data);
	}

	private function saveData() 
	{
		$form_errors = $this->validateForm();
							
		if ($form_errors) {
			$this->data['message']['status'] = 'error';
			$this->data['message']['content'] = $form_errors;
		} else {
			
			$message = $this->model->saveData();
			$this->data = array_merge($this->data, $message);
		}
	}
	private function validateForm() {
	
		$validation =  \Config\Services::validation();
		if (empty($_POST['id'])) {
			$validation->setRule('nama_layanan', 'Nama Layanan', 'trim|required|');
		}
		$validation->withRequest($this->request)->run();
		$form_errors = $validation->getErrors();
				
		return $form_errors;
	}

	private function setEdit() {
		$this->data['title'] = 'Edit Data Layanan';
		$this->data['breadcrumb']['Edit'] = '';
		unset($this->data['breadcrumb']['Add']);
		if (!empty($_POST['id'])) {
			$this->data['id'] = $_POST['id'];
		}
	}
}
