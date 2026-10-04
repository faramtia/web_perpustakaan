<form method="POST" action="{{ url($action) }}" class="d-flex gap-2 mb-3" style="max-width:480px">@csrf<input name="{{ $key }}" class="form-control" placeholder="{{ $label }} baru" required><button class="btn btn-pnl">Tambah</button></form>
<ul class="list-group">
@forelse($rows as $r)
 <li class="list-group-item d-flex justify-content-between align-items-center">{{ data_get($r,$key) }}
  <form method="POST" action="{{ url($action.'/'.data_get($r,'id')) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></li>
@empty<li class="list-group-item text-muted">Belum ada data.</li>@endforelse
</ul>
