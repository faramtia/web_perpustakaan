@php include resource_path('views/pnl/_sample.inc.php'); @endphp
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>@yield('title', 'Perpustakaan') | {{ $lib['nama'] }}</title>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('css/perpus.css') }}" rel="stylesheet">
</head>
<body class="@yield('bodyclass')">
@yield('body')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleGroup(btn){const g=btn.closest('.nav-group'),o=g.classList.contains('open');document.querySelectorAll('.nav-group.open').forEach(x=>{if(x!==g)x.classList.remove('open')});g.classList.toggle('open',!o)}
document.addEventListener('click',e=>{const s=document.getElementById('sidebar');if(s&&innerWidth<=980&&s.classList.contains('open')&&!s.contains(e.target)&&!e.target.closest('.menu-btn'))s.classList.remove('open')});
</script>
@stack('scripts')
</body>
</html>
