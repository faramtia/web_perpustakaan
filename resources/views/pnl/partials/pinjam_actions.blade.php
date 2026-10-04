@if($isAdmin)
@if($r['status']!=='aktif')<form method="POST" action="{{ url('peminjaman/'.data_get($r,'id').'/status') }}" class="d-inline">@csrf @method('PATCH')<input type="hidden" name="status" value="aktif"><button class="btn btn-sm btn-outline-success">Setujui</button></form> @endif
@if($r['status']!=='ditolak')<form method="POST" action="{{ url('peminjaman/'.data_get($r,'id').'/status') }}" class="d-inline">@csrf @method('PATCH')<input type="hidden" name="status" value="ditolak"><button class="btn btn-sm btn-outline-danger">Tolak</button></form> @endif
@if($r['status']!=='dikembalikan')<form method="POST" action="{{ url('peminjaman/'.data_get($r,'id').'/status') }}" class="d-inline">@csrf @method('PATCH')<input type="hidden" name="status" value="dikembalikan"><button class="btn btn-sm btn-outline-info">Kembali</button></form> @endif
@endif
