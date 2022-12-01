<style>
.text-removed, .img-removed i {
    text-decoration: line-through;
    color:#dd4b39;
}
.text-added, .img-added i {
    color:#00a65a;
}
.img-removed img {
    border-color:#dd4b39;
}
.img-added img {
    border-color:#00a65a;
}
</style>

@if (! $action)
    <p class="text-center">Action not found!</p>
    @php 
    return;
    @endphp
@endif

@if ($action->data)
    @if ($action->refId > 0)
        <p style="margin-bottom:15px"><b>View History:</b> <a href="{{ url(config('form-tool.adminURL').'/activities-log') }}?id={{ $action->refId }}&module={{ $action->module }}">#{{ $action->refId }}</a></p>
    @endif
    @if (isset($action->data['copyFrom']))
        @php 
        $dId = $action->data['copyFrom']['refId'];
        if ($action->data['copyFrom']['token']) {
            $dId = $action->data['copyFrom']['token'];
        }
        @endphp
        <p style="margin-bottom:15px"><b>Duplicated From:</b> <a href="{{ url(config('form-tool.adminURL').'/'.$action->route) }}?id={{ $dId }}" target="_blank">#{{ $dId }} &nbsp;<i class="fa fa-external-link"></i></a></p>
    @endif
    <table class="table table-bordered table-hover">
        @foreach ($action->data['data'] as $label => $value)
            <tr>
                <th>{{ $label }}</th>
                <td>
                    @if (! is_array($value))
                        {{ $value }}
                    @else
                        @php 
                        $data1 = $data2 = null;
                        if (! is_array($value['data'])) {
                            $data1 = $value['data'];
                        }
                        else {
                            $data1 = $value['data'][0];
                            $data2 = $value['data'][1];
                        }
                        @endphp

                        @if (isset($value['type']))
                            @switch($value['type'])
                                @case('file')
                                @case('image')
                                    <span class="@if($data2) img-removed @endif">{!! imageThumb($data1) !!}</span> &nbsp;
                                    <span class="img-added">{!! imageThumb($data2) !!}</span>
                                    @break;

                                @case('encrypted')
                                    <span class="@if($data2) text-removed @endif">{!! decryptText($data1) !!}</span>
                                    <span class="text-added">{!! decryptText($data2) !!}</span>
                                    @break;

                                @case('editor')
                                    <div class="@if($data2) text-removed @endif">{!! decodeHTML($data1) !!}</div>
                                    <div class="text-added">{!! decodeHTML($data2) !!}</div>
                                    @break;

                                @case('datetime')
                                    <span class="@if($data2) text-removed @endif">{{ niceDateTime($data1, true) }}</span>
                                    <span class="text-added">{{ niceDateTime($data2, true) }}</span>
                                    @break;
                                    
                                @case('date')
                                    <span class="@if($data2) text-removed @endif">{{ niceDate($data1, true) }}</span>
                                    <span class="text-added">{{ niceDate($data2, true) }}</span>
                                    @break;

                                @case('time')
                                    <span class="@if($data2) text-removed @endif">{{ niceTime($data1, true) }}</span>
                                    <span class="text-added">{{ niceTime($data2, true) }}</span>
                                    @break;

                                @default
                                    <span class="@if($data2) text-removed @endif">{!! $data1 !!}</span>
                                    <span class="text-added">{!! $data2 !!}</span>
                                    @break;

                            @endswitch
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@else
    <p>No data found!</p>
@endif