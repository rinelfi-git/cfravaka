<div class="btn-group">
    <button type="button" class="btn btn-sm btn-default open-update-modal" data-route="{{route('app.list.formations.get')}}" data-id="{{$id}}">
        <i class="fas fa-pen"></i>
    </button>
    <a type="button" class="btn btn-sm btn-default duplicate-record" href="{{route('app.list.formation.duplicate', ['id' => $id])}}">
        <i class="fas fa-copy"></i>
    </a>
</div>