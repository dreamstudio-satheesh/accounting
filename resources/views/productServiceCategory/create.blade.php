{{ Form::open(array('url' => 'product-category')) }}
<div class="modal-body">
    <div class="row">
        <div>
            <a href="#" data-size="md" data-ajax-popup-over="true" data-url="{{ route('generate', ['category']) }}"
                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                data-title="{{ __('Generate content with AI') }}" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-robot"></i>
                {{__('Generate with AI')}}
            </a>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('name', __('Category Name'),['class'=>'form-label']) }}
            {{ Form::text('name', '', array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('type', __('Category Type'),['class'=>'form-label']) }}
            {{ Form::select('type',$types,null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('color', __('Category Color'),['class'=>'form-label']) }}
            {{ Form::color('color', '', array('class' => 'form-control jscolor','required'=>'required')) }}
            <small>{{__('For chart representation')}}</small>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
