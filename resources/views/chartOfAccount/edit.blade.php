{{ Form::model($chartOfAccount, array('route' => array('chart-of-account.update', $chartOfAccount->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div>
            <a href="#" data-size="md" data-ajax-popup-over="true" data-url="{{ route('generate', ['chart of accounts']) }}"
                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                data-title="{{ __('Generate content with AI') }}" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-robot"></i>
                {{__('Generate with AI')}}
            </a>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('code', __('Code'),['class'=>'form-label']) }}
            {{ Form::text('code', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('is_enabled',__('Is Enabled'),array('class'=>'form-label')) }}
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="is_enabled" id="is_enabled" {{$chartOfAccount->is_enabled==1?'checked':''}}>
                <label class="custom-control-label form-check-label" for="is_enabled"></label>
            </div>
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {!! Form::textarea('description', null, ['class'=>'form-control','rows'=>'3']) !!}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}

