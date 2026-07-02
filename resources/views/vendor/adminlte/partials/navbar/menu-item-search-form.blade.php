<li class="nav-item d-none d-sm-block" style="position:relative;">
    <div class="navbar-search-wrapper" style="position:relative;">
        <div class="input-group input-group-sm" style="width:320px;">
            <div class="input-group-prepend">
                <span class="input-group-text bg-transparent border-right-0" style="border-radius:20px 0 0 20px;">
                    <i class="fas fa-search text-muted"></i>
                </span>
            </div>
            <input class="form-control form-control-navbar border-left-0 global-search-input"
                   type="search"
                   placeholder="{{ $item['text'] ?? 'Search...' }}"
                   aria-label="Search"
                   autocomplete="off"
                   style="border-radius:0 20px 20px 0;font-size:14px;background:#f8f9fa;">
        </div>

        {{-- Search results dropdown --}}
        <div class="global-search-results dropdown-menu show" style="display:none;position:absolute;top:100%;left:0;right:0;margin-top:6px;border-radius:14px;border:none;box-shadow:0 8px 30px rgba(0,0,0,0.12);max-height:420px;overflow-y:auto;z-index:9999;width:100%;"></div>
    </div>
</li>

@push('js')
<script>
$(function() {
    var $input = $('.global-search-input');
    var $results = $('.global-search-results');
    var searchTimer;

    $input.on('input', function() {
        var q = $(this).val();
        clearTimeout(searchTimer);

        if (q.length < 2) {
            $results.hide().empty();
            return;
        }

        searchTimer = setTimeout(function() {
            $.getJSON('{{ route("admin.search") }}', { q: q }, function(data) {
                if (!data.results || data.results.length === 0) {
                    $results.html('<div class="text-center py-4 text-muted"><i class="fas fa-search mr-2"></i>No results found</div>').show();
                    return;
                }
                var html = '<div class="list-group list-group-flush">';
                $.each(data.results, function(i, r) {
                    html += '<a href="' + r.url + '" class="list-group-item list-group-item-action d-flex align-items-center px-3 py-2" style="border-left:none;border-right:none;">';
                    html += '<div style="width:32px;height:32px;background:' + r.color + '18;border-radius:8px;display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0;">';
                    html += '<i class="' + r.icon + '" style="color:' + r.color + ';font-size:14px;"></i></div>';
                    html += '<div class="flex-grow-1"><div class="font-weight-bold" style="font-size:13px;">' + r.label + '</div>';
                    html += '<small class="text-muted">' + r.type + '</small></div>';
                    html += '<i class="fas fa-chevron-right text-muted" style="font-size:10px;"></i>';
                    html += '</a>';
                });
                html += '</div>';
                $results.html(html).show();
            });
        }, 300);
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.navbar-search-wrapper').length) {
            $results.hide();
        }
    });

    $input.on('focus', function() {
        if ($results.children().length) $results.show();
    });
});
</script>
@endpush