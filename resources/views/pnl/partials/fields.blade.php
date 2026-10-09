<div class="row g-3">
@foreach($fields as $f)
 @php $t = $f[2] ?? 'text'; @endphp
 <div class="col-md-{{ $f[3] ?? 6 }}"><label class="form-label small fw-semibold">{{ $f[1] }}</label>
 @if($t==='select')<select name="{{ $f[0] }}" class="form-select">@foreach($f[4] as $o)<option value="{{ $o[0] }}">{{ $o[1] }}</option>@endforeach</select>
 @elseif($t==='textarea')<textarea name="{{ $f[0] }}" rows="4" class="form-control"></textarea>
 @else<input type="{{ $t }}" name="{{ $f[0] }}" class="form-control" @if($t==='file') accept="image/*,.pdf" @endif>@endif
 </div>
@endforeach
</div>
