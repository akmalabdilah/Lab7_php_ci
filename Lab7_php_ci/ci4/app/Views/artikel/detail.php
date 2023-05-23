<?= $this->include('template/header'); ?>
<article class="entry">
     <h2><?= $artikel['judul']; ?></h2>
     <img src="<?= base_url('/gambar/' . $artikel['gambar']);?>" width="150px" height="100px" alt="<?=
$artikel['judul']; ?>">
     <p><?= $artikel['isi']; ?></p>
</article>
<?= $this->include('template/footer'); ?>