@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status WhatsApp</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Status Device</h4>
                                </div>
                                <div class="card-body">
                                    <div id="device-status">
                                        <div class="alert alert-info">
                                            Memeriksa status device...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>QR Code</h4>
                                </div>
                                <div class="card-body">
                                    <div id="qr-code">
                                        <div class="alert alert-info">
                                            Memuat QR code...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function checkDeviceStatus() {
    $.get('/admin/whatsapp/status', function(response) {
        if (response.status) {
            $('#device-status').html(`
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Device terhubung dan aktif
                </div>
            `);
        } else {
            $('#device-status').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> Device tidak terhubung atau tidak aktif
                </div>
            `);
        }
    });
}

function getQRCode() {
    $.get('/admin/whatsapp/qr', function(response) {
        if (response.qr) {
            $('#qr-code').html(`
                <div class="text-center">
                    <img src="${response.qr}" alt="QR Code" class="img-fluid">
                    <p class="mt-2">Scan QR code ini dengan WhatsApp di HP Anda</p>
                </div>
            `);
        } else {
            $('#qr-code').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> Gagal memuat QR code
                </div>
            `);
        }
    });
}

// Cek status setiap 30 detik
setInterval(checkDeviceStatus, 30000);

// Load status dan QR code saat halaman dimuat
$(document).ready(function() {
    checkDeviceStatus();
    getQRCode();
});
</script>
@endpush
@endsection 