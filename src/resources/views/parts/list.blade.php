@if (acl_check('part::list'))

@push('page-content')
	@include('pages::parts.part')

	<div id="pageEditParts"></div>

	@if (acl_check('part::create'))
	<div id="pageEditPartsPanel" class="panel-heading">
		{!! Form::button(trans('pages::part.button.create'), [
			'data-icon' => 'plus', 'id' => 'pageEditPartAddButton',
			'data-hotkeys' => 'ctrl+a', 'class' => 'btn btn-default btn-labeled'
		]) !!}
	</div>
	@endif
@endpush

@endif