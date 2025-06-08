<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class WhatsAppService
{
    protected $token;
    protected $baseUrl;

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
        $this->baseUrl = 'https://api.fonnte.com/send';

        // Log konfigurasi saat service diinisialisasi
        Log::info('WhatsAppService initialized', [
            'token_exists' => !empty($this->token),
            'base_url' => $this->baseUrl
        ]);
    }

    /**
     * Mendapatkan QR Code untuk menghubungkan device
     */
    public function getQRCode()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->get('https://api.fonnte.com/qr');

            Log::info('Fonnte QR Code API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['qr'])) {
                    return $data['qr'];
                }
            }

            Log::error('Failed to get QR code', [
                'response_status' => $response->status(),
                'response_body' => $response->body()
            ]);
            return null;
        } catch (RequestException $e) {
            Log::error('Network error getting QR code', [
                'error' => $e->getMessage(),
                'request_uri' => $e->getRequest()->getUri(),
                'response_body' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'N/A'
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Error getting QR code', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Cek status device
     */
    public function checkDeviceStatus()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post('https://api.fonnte.com/device');

            Log::info('Fonnte Device Status API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Cek status device
                if (isset($data['status'])) {
                    if ($data['status'] === true) {
                        Log::info('Device is connected and active');
                        return true;
                    } else {
                        Log::error('Device is not connected or inactive according to Fonnte API', [
                            'device_status_response' => $data
                        ]);
                        return false;
                    }
                }
            }

            Log::error('Failed to check device status: Unexpected API response structure', [
                'response_status' => $response->status(),
                'response_body' => $response->body()
            ]);
            return false;
        } catch (RequestException $e) {
            Log::error('Network error checking device status', [
                'error' => $e->getMessage(),
                'request_uri' => $e->getRequest()->getUri(),
                'response_body' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'N/A'
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error checking device status', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    public function sendMessage($phone, $message)
    {
        try {
            // Log awal pengiriman pesan
            Log::info('Attempting to send WhatsApp message', [
                'original_phone' => $phone,
                'message_length' => strlen($message)
            ]);

            // Bersihkan nomor telepon dari karakter non-numerik
            $phone = preg_replace('/[^0-9]/', '', $phone);
            
            // Pastikan nomor dimulai dengan 62 (kode negara Indonesia)
            if (substr($phone, 0, 2) !== '62') {
                $phone = '62' . ltrim($phone, '0');
            }

            // Log request yang akan dikirim
            Log::info('Sending request to Fonnte API', [
                'phone' => $phone,
                'url' => $this->baseUrl
            ]);

            // Kirim pesan menggunakan Fonnte API
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post($this->baseUrl, [
                'target' => $phone,
                'message' => $message
            ]);

            // Log response dari API
            Log::info('Fonnte Message Send API Response', [
                'phone' => $phone,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['status']) && $responseData['status'] === true) {
                    return true;
                }
                throw new \Exception('Fonnte API returned error: ' . json_encode($responseData));
            }

            throw new \Exception('Fonnte API request failed with status: ' . $response->status() . ' body: ' . $response->body());

        } catch (RequestException $e) {
            Log::error('Network error sending WhatsApp message via Fonnte', [
                'phone' => $phone,
                'message' => $message,
                'error' => $e->getMessage(),
                'request_uri' => $e->getRequest()->getUri(),
                'response_body' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'N/A'
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp message via Fonnte', [
                'phone' => $phone,
                'message' => $message,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Validasi nomor telepon
     */
    public function validateNumber($phone)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token
            ])->post('https://api.fonnte.com/validate', [
                'target' => $phone
            ]);

            Log::info('Fonnte Validate Number API Response', [
                'phone' => $phone,
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return isset($data['status']) && $data['status'] === true;
            }

            return false;
        } catch (RequestException $e) {
            Log::error('Network error validating phone number', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'request_uri' => $e->getRequest()->getUri(),
                'response_body' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : 'N/A'
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Error validating phone number', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
} 