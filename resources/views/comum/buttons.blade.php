<a href="{{route($route . '.edit', $id )}}" class="btn btn-xs btn-default"><i class="gi gi-edit"></i>&nbsp;EDITAR</a>
<a href="{{route($route . '.show', $id )}}" class="btn btn-xs btn-default"><i class="gi gi-search"></i>&nbsp;EXIBIR</a>
{!! Form::open(['method' => 'DELETE', 'route'=>[$route . '.destroy', $id]]) !!}
{!! Form::button('<i class="hi hi-remove"></i>&nbsp;EXCLUIR', ['type' => 'submit', 'class' => 'btn btn-xs btn-danger']) !!}
{!! Form::close() !!}

