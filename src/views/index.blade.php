@extends($layout->extends)

{{-- Page title --}}
@section($layout->header)
  <h1>@lang('variables::variables.page_title')</h1>
@stop

@section($layout->content)

  <div class="row">

    <div class="col-lg-8">

      <div class="box">

        {!! Form::open(array('url' => $edit_url, 'class' => 'form-horizontal')) !!}

        <div class="box-body table-responsive no-padding">

           <table class="table table-hover">

            <thead>
              <tr>
                <th>@lang('variables::variables.table_key')</th>
                <th>@lang('variables::variables.table_value')</th>
              </tr>
            </thead>

            <tbody>

              @foreach ($variables as $key => $variable)
                <tr>
                  <td class="col-sm-3 text-right">
                    {!! Form::label($key, trans('variables.' . $key), array('class' => 'control-label')) !!}
                  </td>
                  <td>
                    {!! Form::text($key, $variable['value'], array('class' => 'form-control')) !!}
                  </td>
                </tr>
              @endforeach

            </tbody>

           </table>

        </div>

        <div class="box-footer clearfix">
          {!! Variables::get("test") !!}
          {!! Form::submit(trans('variables::variables.save'), array('class' => 'btn btn-primary')) !!}
        </div>

        {!! Form::close() !!}

      </div>

    </div>
  </div>

@stop
