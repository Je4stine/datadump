<div id="progressive_milestones" class="tab-pane fade">
    <h3>Progressive Milestones</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Pages</th>
                    <th>Due</th>
                </tr>
                <?php
                $no =1;
                $order_paid = $order->payments()->sum('amount');
                ?>
                @foreach($order->progressiveMilestones()->orderBy('id','asc')->get() as $milestone)
                  <tr>
                        <td>{{ $milestone->id }}</td>
                        <td>{!! $milestone->pages !!}</td>
                        <td>
                         {{ \Carbon\Carbon::createFromTimestamp(strtotime($milestone->deadline))->diffForHumans() }}
                        </td>
                    </tr>
                    @endforeach
                </thead>
            </table>
</div>

