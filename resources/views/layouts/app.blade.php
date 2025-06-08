@if(session('whatsapp_urls'))
    <div class="whatsapp-notification">
        <h4>Link Notifikasi WhatsApp:</h4>
        <ul>
            @foreach(session('whatsapp_urls') as $item)
                <li>
                    <a href="{{ $item['url'] }}" target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i> 
                        Kirim ke {{ $item['name'] }} ({{ $item['type'] }})
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif 