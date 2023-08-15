{{Form::model(null, array('route' => array('custom_page.update', $key), 'method' => 'PUT')) }}
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="form-group col-md-12">
                {{Form::label('name',__('Page Name'),['class'=>'form-label'])}}
                {{Form::text('menubar_page_name',$page['menubar_page_name'],array('class'=>'form-control font-style','placeholder'=>__('Enter Plan Name'),'required'=>'required'))}}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('description', __('Page Content'),['class'=>'form-label']) }}
                {!! Form::textarea('menubar_page_contant', $page['menubar_page_contant'], ['class'=>'form-control','rows'=>'5', 'id'=>'mytextarea']) !!}
            </div>

            <div class="col-lg-2 col-xl-2 col-md-2">
                <div class="form-check form-switch ml-1">
                    <input type="checkbox" class="form-check-input" id="cust-theme-bg" name="header" {{ $page['header'] == 'on' ? 'checked' : "" }} />
                    <label class="form-check-label f-w-600 pl-1" for="cust-theme-bg" >{{__('Header')}}</label>
                </div>
            </div>

            <div class="col-lg-2 col-xl-2 col-md-2">
                <div class="form-check form-switch ml-1">
                    <input type="checkbox" class="form-check-input" id="cust-darklayout" name="footer"{{ $page['footer'] == 'on' ? 'checked' : "" }}/>
                    <label class="form-check-label f-w-600 pl-1" for="cust-darklayout">{{ __('Footer') }}</label>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}


<script>
    tinymce.init({
      selector: '#mytextarea',
      menubar: '',
    });
  </script>
