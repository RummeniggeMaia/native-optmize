{!! Form::open(['method' => 'DELETE', 'route'=>[$route, $id]]) !!}
{!! Form::button('<i class="hi hi-remove"></i>&nbsp;' . (isset($text) ? $text : 'EXCLUIR'), ['type' => 'submit', 'class' => 'btn btn-xs btn-danger']) !!}
{!! Form::close() !!}
