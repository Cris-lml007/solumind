@php
@endphp
<div class="d-flex justify-content-end">
    <div class="d-flex align-items-center my-2">
        <button id="prev-page" class="btn btn-primary me-2">Anterior</button>
        <input type="number" id="page-number" min="1" value="1" style="width: 60px; text-align:center;" wire:keyup="setPage($event.target.value)">
        <button id="next-page" class="btn btn-primary ms-2">Siguiente</button>
        <span id="total-pages" class="ms-2">/ {{ $paginator->lastPage() }}</span>
    </div>
</div>
