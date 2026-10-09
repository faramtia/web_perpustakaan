@if(data_get($r,'file_path'))<a href="{{ asset('storage/'.data_get($r,'file_path')) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Buka file</a>@endif
@if($isAdmin)
<form method="POST" action="{{ url('tugas-akhir/'.data_get($r,'id').'/status') }}" class="d-inline-flex gap-1">@csrf @method('PATCH')
 <input name="catatan_reviewer" class="form-control form-control-sm" style="width:150px" placeholder="Catatan">
 <button name="status" value="disetujui" class="btn btn-sm btn-outline-success">Setujui</button>
 <button name="status" value="ditolak" class="btn btn-sm btn-outline-danger">Tolak</button>
</form>
@endif
