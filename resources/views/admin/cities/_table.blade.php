@forelse($cities as $city)
    <tr>
        <td class="align-middle"><span class="text-muted">#{{ $city->id }}</span></td>
        <td class="align-middle" style="font-weight: 500;">{{ $city->name }}</td>
        <td class="align-middle"><span class="badge badge-secondary" style="border-radius: 20px; padding: 4px 12px;">{{ $city->areas_count }}</span></td>
        <td class="align-middle text-center">
            <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-warning" title="Edit" style="border-radius: 6px 0 0 6px;" onclick="editCity({{ $city->id }}, '{{ $city->name }}')"><i class="fas fa-edit"></i></button>
                <form action="{{ route('admin.cities.destroy', $city->id) }}" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger" title="Delete" style="border-radius: 0 6px 6px 0;" onclick="return confirm('Delete this city?')"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center py-5">
            <i class="fas fa-city text-muted" style="font-size: 48px; opacity: 0.3;"></i>
            <p class="text-muted mt-2 mb-0">No cities found.</p>
        </td>
    </tr>
@endforelse
