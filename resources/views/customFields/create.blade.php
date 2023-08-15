{{ Form::open(array('url' => 'custom-field')) }}
<div class="modal-body">
    <div class="row">
        <div>
            <a href="#" data-size="md" data-ajax-popup-over="true" data-url="{{ route('generate', ['custom field']) }}"
                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                data-title="{{ __('Generate content with AI') }}" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-robot"></i>
                {{__('Generate with AI')}}
            </a>
        </div>
        <div class="form-group col-md-12">
            {{Form::label('name',__('Custom Field Name'),['class'=>'form-label'])}}
            {{Form::text('name',null,array('class'=>'form-control','required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('type', __('Type'),['class'=>'form-label']) }}
            {{ Form::select('type',$types,null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('module', __('Module'),['class'=>'form-label']) }}
            {{ Form::select('module',$modules,null, array('class' => 'form-control','required'=>'required')) }}
        </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
