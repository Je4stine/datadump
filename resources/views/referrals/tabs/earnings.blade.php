<table class="table table-bordered">
    <tr>
        <th>Amount</th>
        <th>Email</th>
        <th>Order ID</th>
        <th>Order Amount</th>
        <th>Action</th>
    </tr>
    @foreach($earnings as $earning)
        <tr>
            <td>{{ '$'.number_format($earning->earnings,2) }}</td>
            <td>{{ $earning->email }}</td>
            <td>{{ $earning->id }}</td>
            <td>{{ number_format($earning->amount,2) }}</td>
            <td>
                <a href="{{ url("order/$earning->id") }}" class="btn btn-primary">Order</a>
                <a href="{{ url("order/user/client/$earning->referred_by") }}" class="btn btn-primary">Client</a>
            </td>
        </tr>
        @endforeach
</table>