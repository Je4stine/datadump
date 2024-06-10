<table style="border-color: #666; font-size:large;" cellpadding="10">
    @foreach($data as $key=>$value)
        <tr>
            <td style='background: #eee;'><strong>{{ $key }}</strong></td>
            <td>
                @if(is_array($value))
                    <table rules="all" style="border-color: #666;" cellpadding="10">
                        <tr>
                            @foreach($value as $key=>$value)
                                <td style='background: #eee;'><strong>{{ $key }}</strong></td>
                                <td>
                                    @if(is_array($value))
                                        <table rules="all" style="border-color: #666;" cellpadding="10">
                                            <tr>
                                                @foreach($value as $key=>$value)
                                                    <td style='background: #eee;'><strong>{{ $key }}</strong></td>
                                                    <td>
                                                        @if(is_array($value))
                                                            <table rules="all" style="border-color: #666;" cellpadding="10">
                                                                <tr>
                                                                    @foreach($value as $key=>$value)
                                                                        <td style='background: #eee;'><strong>{{ $key }}</strong></td>

                                                                        <td>{{ $value }}</td>
                                                                    @endforeach
                                                                </tr>
                                                            </table>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    @else
                                        {{ $value }}
                                    @endif
                                </td>
                                @endforeach
                        </tr>
                    </table>
                    @else
                    {{ $value }}
                @endif
            </td>
        </tr>
   @endforeach
</table>
<style>
    table{
       margin: 2px;
    }
    td{
        padding: 2px;
    }
</style>