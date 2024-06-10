<br/>
<div class="col-md-9">
    <div class="section">
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Referred Client</th>
                <th>Spent Amount</th>
                <th>My Earning</th>
            </tr>
            @foreach($earnings as $earning)
                <tr>
                    <td>{{ $earning->id }}</td>
                    <td>{{ $earning->name }}</td>
                    <td>${{ number_format($earning->order_amount,2) }}</td>
                    <td>${{ number_format($earning->earning,2) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

