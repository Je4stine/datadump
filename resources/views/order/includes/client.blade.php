<div id="o_client" class="tab-pane fade">
    <?php
    $user = $order->user;
    ?>
    <div class="profile_pic">

        <img src="@if($user->image) {{ URL::to($user->image) }} @else {{ URL::to('images/img.png') }} @endif" alt="..." class="img-circle profile_img">
    </div>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>E-mail</th>
            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
        </tr>
        <tr>
            <th>Role</th>
            <td>{{ ucwords($user->role) }}</td>
        </tr>
    </table>
</div>