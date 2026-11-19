@props([
    'action',
    'prefix' => 'export',
    'currentPeriod' => null,
])

<form id="{{ $prefix }}-export-form" method="GET" action="{{ $action }}" class="flex flex-wrap items-center gap-2.5 p-3 rounded-lg border border-gray-200 bg-white">
    <span class="text-xs font-medium text-gray-600">Ekspor Laporan:</span>

    <select name="period_type" id="{{ $prefix }}-period-type" onchange="toggleExportPeriodInput('{{ $prefix }}')"
            class="text-xs rounded-md border border-gray-200 px-2.5 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer">
        <option value="monthly" selected>Bulanan</option>
        <option value="weekly">Mingguan</option>
    </select>

    <input type="month" id="{{ $prefix }}-period-monthly"
           value="{{ $currentPeriod ?: date('Y-m') }}"
           class="text-xs rounded-md border border-gray-200 px-2.5 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-primary">

    <input type="week" id="{{ $prefix }}-period-weekly"
           value="{{ date('Y-\WW') }}"
           class="hidden text-xs rounded-md border border-gray-200 px-2.5 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-primary">

    <input type="hidden" name="period" id="{{ $prefix }}-period-final">
    <input type="hidden" name="format" id="{{ $prefix }}-format-final">

    <div class="flex items-center gap-1.5 ml-auto">
        <button type="button" onclick="submitExport('{{ $prefix }}', 'xlsx')"
                class="px-3 py-1.5 rounded-md border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-medium transition-colors cursor-pointer">
            Unduh Excel
        </button>
        <button type="button" onclick="submitExport('{{ $prefix }}', 'pdf')"
                class="px-3 py-1.5 rounded-md border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-medium transition-colors cursor-pointer">
            Unduh PDF
        </button>
    </div>
</form>

@once
<script>
function toggleExportPeriodInput(prefix) {
    const type = document.getElementById(prefix + '-period-type').value;
    const monthly = document.getElementById(prefix + '-period-monthly');
    const weekly = document.getElementById(prefix + '-period-weekly');
    if (type === 'weekly') {
        monthly.classList.add('hidden');
        weekly.classList.remove('hidden');
    } else {
        weekly.classList.add('hidden');
        monthly.classList.remove('hidden');
    }
}
function submitExport(prefix, format) {
    const form = document.getElementById(prefix + '-export-form');
    const type = document.getElementById(prefix + '-period-type').value;
    const period = type === 'weekly' 
        ? document.getElementById(prefix + '-period-weekly').value 
        : document.getElementById(prefix + '-period-monthly').value;
    document.getElementById(prefix + '-period-final').value = period;
    document.getElementById(prefix + '-format-final').value = format;
    form.submit();
}
</script>
@endonce
