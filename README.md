# TUGAS PERTEMUAN 12

<hr>

**Praktikum 12: Framework Lanjutan (CRUD)**

**Pemrograman Web: Framework Lanjutan (CRUD)**

<hr>

**Instruksi Praktikum**
1. Persiapkan text editor misalnya **VSCode**.
2. Buka kembali folder dengan nama **lab11_php_ci** pada docroot webserver **(htdocs)**
3. Ikuti langkah-langkah praktikum yang akan dijelaskan berikutnya.<br>

**Langkah-langkah Praktikum
Persiapan.**
Untuk memulai membuat aplikasi CRUD sederhana, yang perlu disiapkan adalah 
database server menggunakan MySQL. Pastikan MySQL Server sudah dapat dijalankan 
melalui XAMPP.

**Membuat Database: Studi Kasus Data Artikel**

![11_Lab11Web](Gambar/29.Gambar_Membuat_Database.jpg)

**`Membuat Database`**

```
CREATE DATABASE lab_ci4;
```
![11_Lab11Web](Gambar/30.Gambar_CREATE_DATABASE_lab_ci4.jpg)

Gambar 28.CREATE DATABASE

**`Membuat Tabel`**
```
CREATE TABLE artikel (
id INT(11) auto_increment,
judul VARCHAR(200) NOT NULL,
isi TEXT,
gambar VARCHAR(200),
status TINYINT(1) DEFAULT 0,
slug VARCHAR(200),
PRIMARY KEY(id)
);
```
![11_Lab11Web](Gambar/31.Gambar_Membuat_Tabel.jpg)

Gambar 29.Membuat Tabel

![11_Lab11Web](Gambar/32.Gambar_Membuat_Tabel_Struktur.jpg)

**`Konfigurasi koneksi database`**

Selanjutnya membuat konfigurasi untuk menghubungkan dengan database server.
Konfigurasi dapat dilakukan dengan du acara, yaitu pada file **app/config/database.php**
atau menggunakan file **.env**. Pada praktikum ini kita gunakan konfigurasi pada file .env.

![11_Lab11Web](Gambar/33.Gambar_Konfigurasi_Database.env.jpg)

Gambar 30.Konfigurasi Database

**`Membuat Model`**

Selanjutnya adalah membuat Model untuk memproses data Artikel. Buat file baru pada
direktori **app/Models** dengan nama **ArtikelModel.php**
```
<?php
namespace App\Models;
use CodeIgniter\Model;
class ArtikelModel extends Model
{
protected $table = 'artikel';
protected $primaryKey = 'id';
protected $useAutoIncrement = true;
protected $allowedFields = ['judul', 'isi', 'status', 'slug', 'gambar'];
}
```
![11_Lab11Web](Gambar/34.Gambar_ArtikelModel.php.jpg)

Gambar 31.ArtikelModel.php

**`Membuat Controller`**

Buat Controller baru dengan nama ***Artikel.php*** pada direktori ***app/Controllers***.
```
<?php
namespace App\Controllers;
use App\Models\ArtikelModel;
class Artikel extends BaseController
{
   public function index()
  {
  $title = 'Daftar Artikel';
  $model = new ArtikelModel();
  $artikel = $model->findAll();
  return view('artikel/index', compact('artikel', 'title'));
  }
}
```
![11_Lab11Web](Gambar/35.Gambar_Artikel.php.jpg)

Gambar 32.Artikel.php

**`Membuat View`**

Buat direktori baru dengan nama ***artikel*** pada direktori ***app/views***, kemudian buat file
baru dengan nama ***index.php***.
```
<?= $this->include('template/header'); ?>

<?php if($artikel): foreach($artikel as $row): ?>
    <article class="entry">
    <h2><a href="<?= base_url('/artikel/' . $row['slug']);?>"><?= $row['judul']; ?></a>
</h2>
    <img src="<?= base_url('/gambar/' . $row['gambar']);?>" alt="<?= $row['judul']; ?>">
    <p><?= substr($row['isi'], 0, 200); ?></p>
</article>
<hr class="divider" />
<?php  endforeach; else: ?>
<article class="entry">
    <h2>Belum ada data.</h2>
</article>
<?php endif; ?>
<?= $this->include('template/footer'); ?>
```
![11_Lab11Web](Gambar/36.Gambar_index.php.jpg)

Gambar 33.index.php

Selanjutnya buka browser kembali, dengan mengakses url http://localhost:8080/artikel

![11_Lab11Web](Gambar/38.Gambar_TampilanWeb.Artikel.jpg)

Gambar 34.Tampilan Web.Artikel

Belum ada data yang diampilkan. Kemudian coba tambahkan beberapa data pada
***Database*** agar dapat ditampilkan datanya.
```
INSERT INTO artikel (judul, isi, slug) VALUE
('Artikel pertama', 'Lorem Ipsum adalah contoh teks atau dummy dalam industri
percetakan dan penataan huruf atau typesetting. Lorem Ipsum telah menjadi
standar contoh teks sejak tahun 1500an, saat seorang tukang cetak yang tidak
dikenal mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah
buku contoh huruf.', 'artikel-pertama'),
('Artikel kedua', 'Tidak seperti anggapan banyak orang, Lorem Ipsum bukanlah
teks-teks yang diacak. Ia berakar dari sebuah naskah sastra latin klasik dari
era 45 sebelum masehi, hingga bisa dipastikan usianya telah mencapai lebih
dari 2000 tahun.', 'artikel-kedua');
```
![11_Lab11Web](Gambar/39.Gambar_add_database.Artikel.jpg)

Gambar 35.add database.Artikel

Refresh kembali browser, sehingga akan ditampilkan hasilnya.

![11_Lab11Web](Gambar/40.Gambar_Tampilan_Artikel.jpg)

Gambar 36.Tampilan Artikel

**`Membuat Tampilan Detail Artikel`**

Tampilan pada saat judul berita di klik maka akan diarahkan ke halaman yang berbeda.
Tambahkan fungsi baru pada ***Controller Artikel*** dengan nama ***view()***.
```
public function view($slug)
    {
         $model = new ArtikelModel();
         $artikel = $model->where([
         'slug' => $slug
         ])->first();
         
         // Menampilkan error apabila data tidak ada.
         if (!$artikel)
         {
             throw PageNotFoundException::forPageNotFound();
         }
       $title = $artikel['judul'];
       return view('artikel/detail', compact('artikel', 'title'));
    }
    public function admin_index()
   {
    $title = 'Daftar Artikel';
    $model = new ArtikelModel();
    $artikel = $model->findAll();
    return view('artikel/admin_index', compact('artikel', 'title'));
   }
   ```
![11_Lab11Web](Gambar/41.Gambar_Controller_Artikel.jpg)

Gambar 37.Controller_Artikel

**`Membuat View Detail`**

Buat view baru untuk halaman detail dengan nama ***app/views/artikel/detail.php***.
```
<?= $this->include('template/header'); ?>
<article class="entry">
<h2><?= $artikel['judul']; ?></h2>
<img src="<?= base_url('/gambar/' . $artikel['gambar']);?>" alt="<?=
$artikel['judul']; ?>">
<p><?= $artikel['isi']; ?></p>
</article>
<?= $this->include('template/footer'); ?>
```
![11_Lab11Web](Gambar/42.Gambar_detail.php.jpg)

Gambar 38.View detail.php

**`Membuat Routing untuk artikel detail`**

Buka Kembali file ***app/config/Routes.php***, kemudian tambahkan routing untuk artikel
detail.
```
$routes->get('/artikel/(:any)', 'Artikel::view/$1');
```
![11_Lab11Web](Gambar/37.Gambar_routes.Artikel.jpg)

Gambar 39.routes.Artikel

Refresh kembali browser, sehingga akan ditampilkan hasilnya.

![11_Lab11Web](Gambar/43.Gambar_Detail_Artikel.jpg)

Gambar 40.Detail Artikel

**`Membuat Menu Admin`**

Menu admin adalah untuk proses CRUD data artikel. Buat method baru pada
***Controller Artikel*** dengan nama ***admin_index()***.
```
public function admin_index()
{
$title = 'Daftar Artikel';
$model = new ArtikelModel();
$artikel = $model->findAll();
return view('artikel/admin_index', compact('artikel', 'title'));
}
```
![11_Lab11Web](Gambar/44.Gambar_Controller_Artikel_admin_index.jpg)

Gambar 41.Controller_Artikel_admin_index

Selanjutnya buat view untuk tampilan admin dengan nama ***admin_index.php***
```
<?= $this->include('template/admin_header'); ?>

<table class="table">
    <thead>
       <tr>
          <th>ID</th>
          <th>Judul</th>
          <th>Status</th>
          <th>AKsi</th>
     </tr>
    </thead>
    <tbody>
    <?php if($artikel): foreach($artikel as $row): ?>
    <tr>
       <td><?= $row['id']; ?></td>
       <td>
          <b><?= $row['judul']; ?></b>
          <p><small><?= substr($row['isi'], 0, 50); ?></small></p>
       </td>
       <td><?= $row['status']; ?></td>
       <td>
          <a class="btn" href="<?= base_url('/admin/artikel/edit/' .
$row['id']);?>">Ubah</a>
            <a class="btn btn-danger" onclick="return confirm('Yakin menghapus data?');" href="<?= base_url('/admin/artikel/delete/' .
$row['id']);?>">Hapus</a>
    </td>
    </tr>
    <?php endforeach; else: ?>
    <tr>
       <td colspan="4">Belum ada data.</td>
     </tr>
     <?php endif; ?>
     </tbody>
     <tfoot>
        <tr>
           <th>ID</th>
           <th>Judul</th>
           <th>Status</th>
           <th>AKsi</th>
        </tr>
     </tfoot>
 </table>
 
<?= $this->include('template/admin_footer'); ?>
```
![11_Lab11Web](Gambar/45.Gambar_admin_index.jpg)
![11_Lab11Web](Gambar/45.Gambar_admin_index-1.jpg)

Gambar 42.admin_index.php

Selanjutnya kita buat template halaman admin **di app/Views/template** dengan nama :<br>
`admin_header.php`

`admin_footer.php`

lalu buat css baru **di folder ci4/public** dengan nama :

`adminstyle.css`

Tambahkan routing untuk menu admin seperti berikut:
```
$routes->group('admin', function($routes) {
$routes->get('artikel', 'Artikel::admin_index');
$routes->add('artikel/add', 'Artikel::add');
$routes->add('artikel/edit/(:any)', 'Artikel::edit/$1');
$routes->get('artikel/delete/(:any)', 'Artikel::delete/$1');
});
```
![11_Lab11Web](Gambar/46.Gambar_routing.jpg)

Gambar 43.add routing.php

Akses menu admin dengan url http://localhost:8080/admin/artikel

![11_Lab11Web](Gambar/50.Gambar_Admin_index.jpg)

Gambar 44.Admin index

**`Menambah Data Artikel`**

Tambahkan fungsi/method baru pada **Controller Artikel** dengan nama **add()**.
```
public function add()
{
// validasi data.
$validation = \Config\Services::validation();
$validation->setRules(['judul' => 'required']);
$isDataValid = $validation->withRequest($this->request)->run();
if ($isDataValid)
{
$artikel = new ArtikelModel();
$artikel->insert([
'judul' => $this->request->getPost('judul'),
'isi' => $this->request->getPost('isi'),
'slug' => url_title($this->request->getPost('judul')),
]);
return redirect('admin/artikel');
}
$title = "Tambah Artikel";
return view('artikel/form_add', compact('title'));
}
```
![11_Lab11Web](Gambar/51.Gambar_Controller_add.jpg)

Gambar 45.Controller_add

Kemudian buat view untuk form tambah dengan nama **form_add.php**
```
<?= $this->include('template/admin_header'); ?>
<h2><?= $title; ?></h2>
<form action="" method="post">
<p>
<input type="text" name="judul">
</p>
<p>
<textarea name="isi" cols="50" rows="10"></textarea>
</p>
<p><input type="submit" value="Kirim" class="btn btn-large"></p>
</form>
<?= $this->include('template/admin_footer'); ?>
```
![11_Lab11Web](Gambar/52.Gambar_form_add.php.jpg)

Gambar 46.form_add.php

Akses menu admin dengan url http://localhost:8080/admin/artikel/add

![11_Lab11Web](Gambar/53.Gambar_Tambah_Artikel.jpg)

Gambar 47.Tambah Artikel

**`Mengubah Data`**

Tambahkan fungsi/method baru pada ***Controller Artikel*** dengan nama ***edit()***.
```
public function edit($id)
{
$artikel = new ArtikelModel();
// validasi data.
$validation = \Config\Services::validation();
$validation->setRules(['judul' => 'required']);
$isDataValid = $validation->withRequest($this->request)->run();
if ($isDataValid)
{
$artikel->update($id, [
'judul' => $this->request->getPost('judul'),
'isi' => $this->request->getPost('isi'),
]);
return redirect('admin/artikel');
}
// ambil data lama
$data = $artikel->where('id', $id)->first();
$title = "Edit Artikel";
return view('artikel/form_edit', compact('title', 'data'));
}
```
![11_Lab11Web](Gambar/54.Gambar_Controller_edit.jpg)

Gambar 48.Controller_Function edit

Kemudian buat view untuk form tambah dengan nama ***form_edit.php***
```
<?= $this->include('template/admin_header'); ?>
<h2><?= $title; ?></h2>
<form action="" method="post">
<p>
<input type="text" name="judul" value="<?= $data['judul'];?>" >
</p>
<p>
<textarea name="isi" cols="50" rows="10"><?=
$data['isi'];?></textarea>
</p>
<p><input type="submit" value="Kirim" class="btn btn-large"></p>
</form>
<?= $this->include('template/admin_footer'); ?>
```
![11_Lab11Web](Gambar/55.Gambar_form_edit.php.jpg)

Gambar 49.form_edit.php

Akses menu admin dengan url http://localhost:8080/admin/artikel/edit/1

![11_Lab11Web](Gambar/56.Gambar_Ubah_Artikel.jpg)

Gambar 50.Ubah Artikel

**`Menghapus Data`**

Tambahkan fungsi/method baru pada ***Controller Artikel ***dengan nama ***delete()***.
```
public function delete($id)
{
$artikel = new ArtikelModel();
$artikel->delete($id);
return redirect('admin/artikel');
}
```
![11_Lab11Web](Gambar/57.Gambar_Controller_delete.jpg)

Gambar 51.Controller_delete

<hr>

## **`Pertanyaan dan Tugas`**

<hr>

Selesaikan programnya sesuai Langkah-langkah yang ada. Anda boleh melakukan
improvisasi.

>**Jawab**:

Saya Coba Dengan menambahakan Artikel ketiga,
Akses menu admin dengan url http://localhost:8080/admin/artikel/add

**`Tambah Artikel`**

![11_Lab11Web](Gambar/58.Gambar_tambah_artikel.jpg)

Gambar 52.Tambah_artikel ketiga

![11_Lab11Web](Gambar/59.Gambar_Tampilan_tambah_artikel.jpg)

Gambar 53.Tampilan_tambah_artikel ketiga

![11_Lab11Web](Gambar/60.Gambar_Tampilan_tambah_artikel-1.jpg)

Gambar 54.Tampilan_Portal Berita_artikel ketiga


**`Edit Artikel`**

Akses menu admin dengan url http://localhost:8080/admin/artikel/edit/1

![11_Lab11Web](Gambar/61.Gambar_Tampilan_edit_artikel-1.jpg)

Gambar 55.Edit_artikel

![11_Lab11Web](Gambar/62.Gambar_Tampilan_edit_artikel-2.jpg)

Gambar 56.Tampilan_edit_artikel

![11_Lab11Web](Gambar/62.Gambar_Tampilan_edit_artikel-3.jpg)

Gambar 57.Tampilan_Portal Berita_edit_artikel pertama

**`Hapus Artikel`**

Akses menu admin dengan url http://localhost:8080/admin/artikel/

![11_Lab11Web](Gambar/63.Gambar_Tampilan_hapus.jpg)

Gambar 58.Hapus_artikel

![11_Lab11Web](Gambar/63.Gambar_Tampilan_hapus-1.jpg)

Gambar 59.Tampilan_Hapus_artikel

![11_Lab11Web](Gambar/63.Gambar_Tampilan_hapus-2.jpg)

Gambar 60.Tampilan_Portal Berita_Hapus_artikel ketiga

<hr>

