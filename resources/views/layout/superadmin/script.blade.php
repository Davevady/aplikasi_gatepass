    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <!-- jQuery UI -->
    <script src="../assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="../assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
    <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Chart JS -->
    <script src="../assets/js/plugin/chart.js/chart.min.js"></script>
    <!-- jQuery Sparkline -->
    <script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
    <!-- Chart Circle -->
    <script src="../assets/js/plugin/chart-circle/circles.min.js"></script>
    <!-- Datatables -->
    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Bootstrap Notify -->
    <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <!-- jQuery Vector Maps -->
    <script src="../assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
    <script src="../assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>
    <!-- Sweet Alert -->
    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
    <!-- Atlantis JS -->
    <script src="../assets/js/atlantis.js"></script>
    <!-- Atlantis DEMO methods, don't include it in your project! -->
    {{-- <script src="../assets/js/setting-demo.js"></script>
    <script src="../assets/js/demo.js"></script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleButton = document.getElementById('toggleSidebar');
            var sidebar = document.getElementById('sidebar');

            toggleButton.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        });

        // Ubah logika pencarian agar jika salah satu kata kunci ada di data-name, item tetap muncul
        // Pencarian sidebar agar sub menu juga bisa dicari
        document.getElementById('searchInputSidebar').addEventListener('input', function() {
            var filter = this.value.toLowerCase().trim();
            var keywords = filter.split(/\s+/); // pisahkan berdasarkan spasi

            // Ambil semua nav-item (menu utama) dan sub-item (submenu)
            var mainItems = document.querySelectorAll('#sidebarMenu > .nav-item');
            var subItems = document.querySelectorAll('#sidebarMenu .nav-collapse .nav-item, #sidebarMenu .nav-collapse li[data-name]');

            // Sembunyikan semua menu utama dan submenu dulu
            mainItems.forEach(function(item) {
                item.style.display = '';
                // Tutup collapse submenu
                var collapse = item.querySelector('.collapse');
                if (collapse) {
                    collapse.classList.remove('show');
                }
            });
            subItems.forEach(function(sub) {
                sub.style.display = '';
            });

            if (filter === '') {
                // Jika kosong, tampilkan semua
                mainItems.forEach(function(item) {
                    item.style.display = '';
                    var collapse = item.querySelector('.collapse');
                    if (collapse) {
                        collapse.classList.remove('show');
                    }
                });
                subItems.forEach(function(sub) {
                    sub.style.display = '';
                });
                return;
            }

            // Proses pencarian
            mainItems.forEach(function(item) {
                var cocokMain = false;
                var textMain = (item.getAttribute('data-name') || '').toLowerCase();
                // Cek menu utama
                cocokMain = keywords.some(function(kata) {
                    return textMain.includes(kata);
                });

                // Cek submenu
                var subMenu = item.querySelectorAll('.nav-collapse > li[data-name], .nav-collapse .nav-item[data-name]');
                var cocokSub = false;
                subMenu.forEach(function(sub) {
                    var textSub = (sub.getAttribute('data-name') || '').toLowerCase();
                    var cocok = keywords.some(function(kata) {
                        return textSub.includes(kata);
                    });
                    if (cocok) {
                        sub.style.display = '';
                        cocokSub = true;
                    } else {
                        sub.style.display = 'none';
                    }
                });

                // Tampilkan menu utama jika cocok, atau ada submenu yang cocok
                if (cocokMain || cocokSub) {
                    item.style.display = '';
                    // Jika ada submenu yang cocok, buka collapse-nya
                    if (cocokSub) {
                        var collapse = item.querySelector('.collapse');
                        if (collapse) {
                            collapse.classList.add('show');
                        }
                    }
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Sembunyikan alert setelah 3 detik
        setTimeout(function() {
            $('.floating-alert .alert').fadeOut('slow');
        }, 3000);
    </script>
