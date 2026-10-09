@if($isAdmin)
<form method="POST" action="{{ url('reservasi/'.data_get($r,'id').'/status') }}" class="d-inline">@csrf @method('PATCH')<input type="hidden" name="status" value="tersedia"><button class="btn btn-sm btn-outline-success">Tersedia</button></form> <form method="POST" action="{{ url('reservasi/'.data_get($r,'id').'/status') }}" class="d-inline">@csrf @method('PATCH')<input type="hidden" name="status" value="dibatalkan"><button class="btn btn-sm btn-outline-danger">Batalkan</button></form> 
@endif
