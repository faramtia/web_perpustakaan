@php $mid = 'm'.md5($action); $ro = $readonly ?? false; $showAct = !$ro || isset($extra); @endphp
@include('pnl.partials.head', ['title'=>$title, 'sub'=>$sub ?? ''])
<div class="card">
 <div class="card-head">
  <input type="search" class="form-control form-control-sm" style="max-width:260px" placeholder="Cari..." data-filter="#t{{ $mid }}">
  @unless($ro)<button class="btn btn-sm btn-pnl" data-bs-toggle="modal" data-bs-target="#{{ $mid }}">+ Tambah</button>@endunless
 </div>
 <div class="table-responsive"><table class="table align-middle" id="t{{ $mid }}">
  <thead><tr><th>No</th>@foreach($cols as $c)<th>{{ $c[0] }}</th>@endforeach @if($showAct)<th></th>@endif</tr></thead>
  <tbody>
  @forelse($rows as $r)
   <tr><td>{{ $loop->iteration }}</td>
   @foreach($cols as $c)
    @php $v = data_get($r, $c[1]); @endphp
    <td>@if(($c[2] ?? '')==='badge')<span class="badge {{ $badge($v) }}">{{ $v }}</span>@else{{ $v }}@endif</td>
   @endforeach
   @if($showAct)<td class="text-nowrap">
    @unless($ro)
    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#{{ $mid }}" data-edit="{{ data_get($r,'id') }}" data-row="{{ json_encode($r) }}">Edit</button>
    <form method="POST" action="{{ url($action.'/'.data_get($r,'id')) }}" class="d-inline" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form>
    @endunless
    @isset($extra)@include($extra, ['r'=>$r])@endisset
   </td>@endif
   </tr>
  @empty
   <tr><td colspan="{{ count($cols)+2 }}" class="text-center text-muted">Belum ada data.</td></tr>
  @endforelse
  </tbody></table></div>
</div>
@unless($ro)
<div class="modal fade" id="{{ $mid }}" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
 <div class="modal-header"><h5 class="modal-title">{{ $title }}</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
 <form method="POST" action="{{ url($action) }}" data-base="{{ url($action) }}" enctype="multipart/form-data">@csrf<input type="hidden" name="_method" value="POST">
  <div class="modal-body">@include('pnl.partials.fields', ['fields'=>$fields])</div>
  <div class="modal-footer"><button class="btn btn-pnl">Simpan</button></div>
 </form></div></div></div>
@endunless
@once
@push('scripts')
<script>
document.querySelectorAll('[data-filter]').forEach(i=>i.addEventListener('input',e=>{const q=e.target.value.toLowerCase();document.querySelectorAll(i.dataset.filter+' tbody tr').forEach(r=>r.hidden=!r.textContent.toLowerCase().includes(q))}));
document.addEventListener('click',e=>{const b=e.target.closest('[data-bs-toggle=modal]');if(!b)return;const f=document.querySelector(b.dataset.bsTarget).querySelector('form');f.reset();
 if(b.dataset.edit){const r=JSON.parse(b.dataset.row);f.action=f.dataset.base+'/'+b.dataset.edit;f._method.value='PUT';[...f.elements].forEach(x=>{if(x.name&&x.type!=='file'&&x.name!=='password'&&x.name!=='_token'&&x.name!=='_method'&&r[x.name]!=null)x.value=r[x.name]})}
 else{f.action=f.dataset.base;f._method.value='POST'}});
</script>
@endpush
@endonce
