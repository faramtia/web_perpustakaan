<a href="{{ url('event/'.data_get($r,'id').'/peserta') }}" class="btn btn-sm btn-outline-primary">Peserta</a>
<form method="POST" action="{{ url('event/'.data_get($r,'id').'/daftar') }}" class="d-inline">@csrf<button class="btn btn-sm btn-pnl">Daftar</button></form>
