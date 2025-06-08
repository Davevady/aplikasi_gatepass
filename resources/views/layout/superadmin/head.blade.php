
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>SISIK - {{ $title }}</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="{{ asset('assets/img/icon.ico') }}" type="image/x-icon"/>

	<!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<!-- Bootstrap Icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

	<!-- Fonts and icons -->
	<script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {"families": ["Lato:300,400,700,900"]},
            custom: {"families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], "urls": ["{{ asset('assets/css/fonts.min.css') }}"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/atlantis.css') }}">
	
	<!-- DataTables CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
	
    <style>
        .floating-alert {
            position: fixed;
            right: 50px;
            z-index: 1050;
            min-width: 300px;
            margin-bottom: 10px;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        .floating-alert-container {
            position: fixed;
            bottom: 70px;
            right: 50px;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertContainer = document.createElement('div');
            alertContainer.className = 'floating-alert-container';
            document.body.appendChild(alertContainer);

            const alerts = document.querySelectorAll('.floating-alert');
            alerts.forEach((alert, index) => {
                const bottomPosition = 70 + (index * (alert.offsetHeight + 10));
                alert.style.bottom = `${bottomPosition}px`;
                alert.style.opacity = '1';
                alertContainer.appendChild(alert);
            });
        });
    </script>
