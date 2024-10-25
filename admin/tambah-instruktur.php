<?php
session_start();
include 'koneksi.php';

// jika button simpan di tekan
if (isset($_POST['simpan'])) {
    $nama_instruktur = $_POST['nama_instruktur'];
    $jurusan_instruktur = $_POST['jurusan_instruktur'];



    // $_POST : form input name=''
    // $_GET : url ? parameter+'nilai'
    // $_FILES: mengambil nilai dari input type file

    if (isset($_FILES['foto']['name'])) {
        $nama_foto = $_FILES['foto']['name'];
        $ukuran_foto = $_FILES['foto']['size'];


        // tipe extensi foto
        $ext = array('png', 'jpeg', 'jpg');
        $extFoto = pathinfo($nama_foto, PATHINFO_EXTENSION);

        // JIKA EXTENSI FOTO TIDAK ADA YANG TERDAFTAR DI ARRAY EXTENSI
        if (!in_array($extFoto, $ext)) {
            echo "Ext tidak ditemukan";
            die;
        } else {
            // PINDAKAN GAMBAR DARI TMP KE FOLDER YANG SUDAH KITA BUAT
            move_uploaded_file($_FILES['foto']['tmp_name'], 'upload/' . $nama_foto);
            $insert = mysqli_query($koneksi, "INSERT INTO instruktur (nama_instruktur, jurusan_instruktur, foto) VALUES ('$nama_instruktur','$jurusan_instruktur','$nama_foto')");
        }
    } else {
        $insert = mysqli_query($koneksi, "INSERT INTO instruktur (nama_instruktur, jurusan_instruktur) VALUES ('$nama_instruktur','$jurusan_instruktur')");
    }


    header("location:instruktur.php?tambah=berhasil");
}
// query untuk mengedit
$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$queryEdit = mysqli_query($koneksi, "SELECT * FROM instruktur WHERE id ='$id'");
$rowEdit = mysqli_fetch_assoc($queryEdit);

// jika button edit di klik
if (isset($_POST['edit'])) {
    $nama_instruktur = $_POST['nama_instruktur'];
    $jurusan_instruktur = $_POST['jurusan_instruktur'];
    //jik user ingin memasukan gambar
    if (!empty($_FILES['foto']['name'])) {
        $nama_foto = $_FILES['foto']['name'];
        $ukuran_foto = $_FILES['foto']['size'];


        // tipe extensi foto
        $ext = array('png', 'jpeg', 'jpg');
        $extFoto = pathinfo($nama_foto, PATHINFO_EXTENSION);
        if (!in_array($extFoto, $ext)) {
            echo "Extensi gambar tidak di temukan";
            die;
        } else {
            unlink('upload/' . $rowEdit['foto']);
            move_uploaded_file($_FILES['foto']['tmp_name'], 'upload/' . $nama_foto);
            //coding ubah/update
            $update = mysqli_query($koneksi, "UPDATE instruktur SET nama_instruktur='$nama_instruktur',jurusan_instruktur='$jurusan_instruktur',foto='$nama_foto' WHERE id='$id'");
            header("location:instruktur.php?ubah=berhasil");
        }
    } else {
        // kalau user tidak ingin memasukan gambar
        $update = mysqli_query($koneksi, "UPDATE instruktur SET nama_instruktur='$nama_instruktur',jurusan_instruktur='$jurusan_instruktur' WHERE id='$id'");
        header("location:instruktur.php?ubah=berhasil");
    }
}


?>
<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html
    lang="en"
    class="light-style layout-menu-fixed"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <?php include 'inc/head.php'; ?>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <?php include 'inc/sidebar.php'; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php include 'inc/nav.php'; ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header"><?php echo isset($_GET['edit']) ? 'Edit' : 'Tambah' ?> Instruktur</div>
                                    <div class="card-body">

                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="mb-3 row">
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Nama Instruktur</label>
                                                    <input type="text"
                                                        class="form-control"
                                                        name="nama_instruktur"
                                                        placeholder="Masukan nama anda" required value="<?php echo isset($_GET['edit']) ? $rowEdit['nama_instruktur'] : '' ?>">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Jurusan Instruktur</label>
                                                    <input type="text"
                                                        class="form-control"
                                                        name="jurusan_instruktur"
                                                        placeholder="Masukan jurusan instruktur" required value="<?php echo isset($_GET['edit']) ? $rowEdit['jurusan_instruktur'] : '' ?>">
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <div class="col-sm-6">
                                                    <label for="" class="form-label">Foto</label>
                                                    <input class="form-control" type="file"
                                                        name="foto">
                                                    <style>
                                                        .edit-foto-instruktur {
                                                            border-radius: 10px;
                                                            width: 100%;
                                                            border: solid 1px black;
                                                        }
                                                    </style>
                                                    <img class="edit-foto-instruktur mt-3" width="100" src="upload/<?php echo isset($rowEdit['foto']) ? $rowEdit['foto'] : '' ?>" alt="">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-success" name="<?php echo isset($_GET['edit']) ? 'edit' : 'simpan' ?>" type="submit">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , made with ❤️ by
                                <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a>
                            </div>
                            <div>
                                <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                                <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                                <a
                                    href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/"
                                    target="_blank"
                                    class="footer-link me-4">Documentation</a>

                                <a
                                    href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                                    target="_blank"
                                    class="footer-link me-4">Support</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/admin/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/admin/assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/admin/assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/admin/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/admin/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/admin/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/admin/assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>