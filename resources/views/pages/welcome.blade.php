<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>KajiLah</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets\icons\KAJILAH_V2.svg') }}" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet"
        type="text/css" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet"
        type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="assets/css/styles.css" rel="stylesheet" />

    <style>
        .green-link {
            color: green;
        }

        .green-link:hover {
            text-decoration: underline;
        }

        .green-link>i:hover {
            color: #28a745 !important;
            transform: scale(1.2) !important;
            transition: transform 0.2s ease, color 0.2s ease !important;
        }

        .masthead::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Semi-transparent overlay */
            z-index: 1;
        }

        .masthead .text-white {
            position: relative;
            z-index: 2;
        }

        .shadow-navbar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            /* Subtle shadow */
            transition: box-shadow 0.3s ease;
        }

        .shadow-navbar:hover {
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.25);
            /* Slightly deeper shadow on hover */
        }

        .features-icons {
            padding: 0;
            margin-top: 0;
            /* Remove top margin to reduce gap from previous elements */
            margin-bottom: 0;
            /* Remove bottom margin to reduce gap to next elements */
        }



        @media (max-width: 768px) {
            .masthead h1 {
                font-size: 1.5rem;
                /* Adjust font size for mobile */
                padding: 0 1rem;
                /* Add padding to avoid text overflow */
            }
        }
    </style>

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-light bg-light fixed-top shadow-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <!-- Brand Logo -->
            <div class="d-flex align-items-center">
                <a href="{{ route('welcome') }}">
                    <img src="{{ url('dist/img/LOGO_KAJILAH.svg') }}" alt="logo" class="brand-logo"
                        style="height: 50px; margin-right: 15px;">
                </a>
            </div>
            <!-- Smaller Sign In Button -->
            <a class="btn btn-success btn-sm" href="{{ route('login') }}">Log in</a>
        </div>
    </nav>

    <!-- Masthead -->
    <header class="masthead"
        style="background: url('{{ asset('assets/images/wisma.png') }}') no-repeat center center; background-size: cover; min-height: 40vh; position: relative;">
        <!-- Overlay -->
        <div
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1;">
        </div>
        <!-- Content -->
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-xl-8 text-center">
                    <!-- School Logo -->
                    <div class="mb-4" style="position: relative; z-index: 2;">
                        <img src="{{ asset('assets/images/logoSekolah.svg') }}" alt="Logo Sekolah"
                            style="max-height: 150px;">
                    </div>
                    <!-- Main Title -->
                    <div class="text-white" style="position: relative; z-index: 2;">
                        <h1 style="font-weight: bold; font-family: 'Poppins', Arial, sans-serif; padding: 5vh 0 2vh;">
                            Selamat Datang ke Portal Analisis Peperiksaan KAFA Kelas Pengajian As-Saadah
                        </h1>
                        <!-- Subtitle -->
                        <h2>" Pembentukan & Penerapan Karakter Al-Hikmah "</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <!-- Icons Grid-->
    <section class="features-icons bg-light text-center py-5">
        <div class="container">
            <h2 class="mb-5" style="color: green; font-weight: bold; font-family: 'Poppins'"> Ikuti Perkembangan Sekolah
                Kami </h2>
            <div class="row">
                <!-- Facebook -->
                <div class="col-lg-4">
                    <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3 text-center">
                        <div class="features-icons-icon d-flex justify-content-center align-items-center">
                            <a href="https://www.facebook.com/kafaassaadahjengka/?locale=ms_MY" target="_blank"
                                rel="noopener noreferrer" class="green-link">
                                <i class="bi-facebook text-success"></i>
                            </a>
                        </div>
                        <h3>Facebook</h3>
                        <p class="lead mb-0">KAFA Kelas Pengajian As-Saadah</p>
                    </div>
                </div>

                <!-- Youtube -->
                <div class="col-lg-4">
                    <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3 text-center">
                        <div class="features-icons-icon d-flex justify-content-center align-items-center">
                            <a href="https://www.youtube.com/@abimjengka903" target="_blank" rel="noopener noreferrer"
                                class="green-link">
                                <i class="bi-youtube text-success"></i>
                            </a>
                        </div>
                        <h3>Youtube</h3>
                        <p class="lead mb-0">Angkatan Belia Islam (ABIM) Jengka</p>
                    </div>
                </div>

                <!-- Email -->
                <div class="col-lg-4">
                    <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3 text-center">
                        <div class="features-icons-icon d-flex justify-content-center align-items-center">
                            <a href="mailto:abimjengka@gmail.com" target="_blank" rel="noopener noreferrer"
                                class="green-link">
                                <i class="bi-envelope-fill text-success"></i>
                            </a>
                        </div>
                        <h3>Email</h3>
                        <p class="lead mb-0">Emel ke abimjengka@gmail.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Showcases-->
    <section class="showcase">
        <div class="container-fluid p-0">
            <!-- Showcase 1 -->
            <div class="row g-0">
                <div class="col-lg-6 order-lg-2 text-white showcase-img"
                    style="background-image: url('assets/images/Gambar1.svg')"></div>
                <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                    <h2 style="color: green; font-weight: bold; font-family: 'Poppins'"> Sukatan Pelajaran Komprehensif
                    </h2>
                    <p class="lead mb-0">Kami menyediakan dua sukatan pelajaran utama:
                        Yayasan Takmir Pendidikan (YTP) dan Kelas Al-Quran & Fardhu Ain (KAFA), yang diiktiraf oleh
                        Jabatan Kemajuan Islam Malaysia
                        (JAKIM).</p>
                </div>
            </div>
            <!-- Showcase 2 -->
            <div class="row g-0">
                <div class="col-lg-6 text-white showcase-img"
                    style="background-image: url('assets/images/Gambar2.svg')"></div>
                <div class="col-lg-6 my-auto showcase-text">
                    <h2 style="color: green; font-weight: bold; font-family: 'Poppins'"> Sijil Pendidikan Berkualiti
                    </h2>
                    <p class="lead mb-0">Pelajar berpeluang untuk mengambil Ujian Penilaian Kelas KAFA (UPKK) dan Sijil
                        Penilaian Rendah Agama (SPRA), yang merupakan tanda aras kecemerlangan dalam bidang pendidikan
                        agama
                        Islam.</p>
                </div>
            </div>
            <!-- Showcase 3 -->
            <div class="row g-0">
                <div class="col-lg-6 order-lg-2 text-white showcase-img"
                    style="background-image: url('assets/images/Gambar3.svg')"></div>
                <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                    <h2 style="color: green; font-weight: bold; font-family: 'Poppins'"> Waktu Pembelajaran Ideal </h2>
                    <p class="lead mb-0">Kelas dijalankan pada waktu petang, dari pukul 2:30 petang hingga 5:30 petang,
                        setiap hari Isnin hingga Jumaat, memberikan peluang kepada pelajar untuk seimbangkan pendidikan
                        agama dan akademik.</p>
                </div>
            </div>
            <!-- Showcase 4 -->
            <div class="row g-0">
                <div class="col-lg-6 text-white showcase-img"
                    style="background-image: url('assets/images/Gambar4.svg')"></div>
                <div class="col-lg-6 my-auto showcase-text">
                    <h2 style="color: green; font-weight: bold; font-family: 'Poppins'"> Keselesaan Dalam Pembelajaran
                    </h2>
                    <p class="lead mb-0">Kami menyediakan bilik darjah yang lengkap dengan penghawa dingin untuk
                        memastikan
                        pelajar belajar dalam suasana yang selesa dan kondusif. Kualiti persekitaran pembelajaran adalah
                        keutamaan kami.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Canva Banner -->
    <section>
        <div style="position: relative; width: 100%; height: 0; padding-top: 50.0000%;
 padding-bottom: 0; box-shadow: 0 2px 8px 0 rgba(63,69,81,0.16); overflow: hidden;
 border-radius: 8px; will-change: transform;">
            <iframe loading="lazy"
                style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; border: none; padding: 0;margin: 0;"
                src="https://www.canva.com/design/DAGOiXOjFgg/v9cPG82oZXUPG2PwZpBnoA/view?embed"
                allowfullscreen="allowfullscreen" allow="fullscreen">
            </iframe>
        </div>
    </section>

    <!-- Call to Action: Pendaftaran Pelajar -->
    <section class="call-to-action text-white text-center bg-success" id="registration"
        style="font-family: 'Poppins', sans-serif;">
        <div class="container position-relative py-5">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <!-- Title -->
                    <h2 class="mb-5 fw-bold">
                        Pendaftaran Pelajar Sesi 2025
                    </h2>

                    <!-- Event Details -->
                    <div class="card bg-light text-dark shadow-lg border-0 mb-4">
                        <div class="card-body">
                            <p class="lead mb-2">
                                📅 <strong>Tarikh:</strong> 28 & 29 Disember 2024 (Sabtu & Ahad)
                            </p>
                            <p class="lead mb-2">
                                🕰️ <strong>Masa:</strong> 8.30 pagi - 12.00 tengah hari
                            </p>
                            <p class="lead mb-0">
                                🏬 <strong>Tempat:</strong>
                                <a href="https://goo.gl/maps/C9i4FFoGcV4VJKkn9" target="_blank"
                                    class="text-success text-decoration-underline fw-bold">
                                    Wisma Darul Al-Hikmah Abim Jengka
                                </a>
                            </p>
                            <!-- Additional Links -->
                            <div class="row text-center mt-4">
                                <div class="col-md-4 mb-3">
                                    <a href="https://drive.google.com/file/d/1-oOB5SO7avm29x8ZMQ0qWrTrvDcNJ9KI/view?usp=sharing"
                                        class="text-decoration-underline d-block text-dark fw-bold" target="_blank"
                                        rel="noopener noreferrer">
                                        <i class="bi bi-file-earmark-text me-2"></i> Senarai Bayaran Tahunan
                                    </a>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <a href="https://drive.google.com/file/d/1I0rY4ax5C2e2tPQGiYJOivvrX1651vf5/view?usp=sharing"
                                        class="text-decoration-underline d-block text-dark fw-bold" target="_blank"
                                        rel="noopener noreferrer">
                                        <i class="bi bi-book me-2"></i> Senarai Harga Buku
                                    </a>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <a href="https://drive.google.com/file/d/10ead3VMT6cVfWaHDg41FaCOH3r7JdI_K/view?usp=sharing"
                                        class="text-decoration-underline d-block text-dark fw-bold" target="_blank"
                                        rel="noopener noreferrer">
                                        <i class="bi bi-bag me-2"></i> Senarai Harga Baju
                                    </a>
                                </div>
                            </div>
                            <!-- Payment Information -->
                            <div class="card shadow-lg border-0 mb-4"
                                style="background-color: #2b2b2b; color: #f8f9fa;">
                                <div class="card-body">
                                    <h5 class="fw-bold text-danger mb-4">Bayaran Online</h5>
                                    <div class="p-3" style="background-color: #444; border-radius: 10px;">
                                        <!-- Bank Image -->
                                        <div class="mb-3">
                                            <img src="{{ asset('assets/images/BankIslam.png') }}" alt="Bank Islam Logo"
                                                style="max-width: 170px; height: auto; border-radius: 5px;">
                                        </div>
                                        <p class="mb-2">
                                            <strong class="text-light">No. Akaun:</strong> <span
                                                class="text-white">06037010044056</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong class="text-light">Nama Akaun:</strong> <span
                                                class="text-white">KAFA KELAS PENGAJIAN ISLAM AS-SAADAH</span>
                                        </p>
                                        {{-- <p class="mb-2">
                                            <strong class="text-light">Bank:</strong> <span class="text-white">BANK
                                                ISLAM</span>
                                        </p> --}}
                                    </div>
                                    <small class="text-muted d-block mt-3">
                                        <i class="bi bi-info-circle text-danger"></i> Digalakkan membuat bayaran
                                        sekurang-kurangnya 50% jika terdapat kekangan.
                                    </small>
                                </div>
                            </div>
                            <i class="bi bi-info-circle text-danger"></i> Sekiranya terdapat pertanyaan atau persoalan,
                            sila hubungi: <br>
                            <a href="https://wa.me/+60132152375" class="fw-bold px-5" target="_blank"
                                rel="noopener noreferrer" style="transition: transform 0.2s;">
                                Whatsapp Ustazah Aida
                            </a>

                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <a href="https://app.smap.my/reg/add/kafaassaadah"
                            class="btn btn-light btn-lg fw-bold px-5 shadow-sm" target="_blank"
                            rel="noopener noreferrer" style="transition: transform 0.2s;">
                            Daftar Online Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Background Effect -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-gradient" style="opacity: 0.2; z-index: -1;"></div>
    </section>


    <!-- Location Section -->
    <section class="location text-center bg-white py-5">
        <div class="container">
            <h2 class="" style="color: green; font-weight: bold; font-family: 'Poppins'"> Lokasi Sekolah </h2>
            <p class="">Kunjungi lokasi sekolah kami dengan klik peta di bawah atau gunakan butang di bawah
                untuk terus ke Google Maps.</p>

            <!-- Embedded Map -->
            <div class="mb-4">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4743.171564617916!2d102.54100287569253!3d3.77668714897321!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31c930d9a5a42667%3A0xa92534ff9e14f7ef!2sWisma%20Darul%20Hikmah%20Abim%20Jengka!5e1!3m2!1sen!2smy!4v1734251527455!5m2!1sen!2smy"
                    width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- Google Maps Button -->
            <a href="https://maps.app.goo.gl/ShjXpoxR6k96s6rm6" target="_blank" rel="noopener noreferrer"
                class="btn btn-success">
                Buka di Google Maps
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer bg-dark text-white text-center">
        <div class="container py-4">
            <div class="row align-items-center">
                <!-- Left Section: Links -->
                <div class="col-lg-6 text-center text-lg-start my-auto">
                    <ul class="list-inline mb-2">
                        <li class="list-inline-item"><a href="" class="text-decoration-none text-light">About</a></li>
                        <li class="list-inline-item">⋅</li>
                        <li class="list-inline-item"><a href="" class="text-decoration-none text-light">Contact</a></li>
                        <li class="list-inline-item">⋅</li>
                        <li class="list-inline-item"><a href="" class="text-decoration-none text-light">Terms of Use</a>
                        </li>
                        <li class="list-inline-item">⋅</li>
                        <li class="list-inline-item"><a href="" class="text-decoration-none text-light">Privacy
                                Policy</a></li>
                    </ul>
                    <strong>Copyright &copy; 2025 KajiLah - Kafa Kelas Pengajian Islam As-Saadah.</strong>
                    All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

</body>

</html>
