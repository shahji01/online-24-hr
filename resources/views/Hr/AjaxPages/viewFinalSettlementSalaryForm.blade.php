<div class="row" style="padding-top: 2%;padding-bottom: 2%;">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">
                   <table class="table table-sm mb-0 table-bordered table-striped ">
                        <thead>
                            <tr>
                                <input type="hidden" name="hidden_salary" id="hidden_salary" value="{{ $salary_amount }}">
                                <th class="text-center">Month - Year</th>
                                <th class="text-center">Days</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $y)
                                <?php
                                $month = explode('-', $month_array[$key]);
                                $days = explode('_', $data[$key]);
                                $dt = DateTime::createFromFormat('!m', $month[0]);
                                ?>
                                <tr>
                                    <td class="text-center">{{ date('M', strtotime($dt->format('F'))) . ' - ' . $month[1] }}
                                    </td>
                                    <td class="text-center">{{ $days[0] }}</td>
                                    <td class="text-right">{{ number_format($salary_array[$key]) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
               
            </div>
        </div>
    </div>
</div>
