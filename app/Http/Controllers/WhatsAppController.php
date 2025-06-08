<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Menampilkan halaman status WhatsApp
     */
    public function index()
    {
        return view('superadmin.whatsapp-status');
    }

    /**
     * Mendapatkan status device WhatsApp
     */
    public function getStatus()
    {
        $status = $this->whatsappService->checkDeviceStatus();
        return response()->json(['status' => $status]);
    }

    /**
     * Mendapatkan QR code untuk menghubungkan device
     */
    public function getQR()
    {
        $qr = $this->whatsappService->getQRCode();
        return response()->json(['qr' => $qr]);
    }
} 