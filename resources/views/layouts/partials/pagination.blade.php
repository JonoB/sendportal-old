<div class="d-flex align-items-center mt-3 mb-5">
    <div class="d-flex flex-grow-1">
        {{ $records->links() }}
    </div>

    <div class="mb-0 mr-1 fc-gray-500">Showing {{ $records->count() }} of {{ $records->total() }} records</div>
</div>