<div class="box-body">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-sm-2">&nbsp;</th>
                    <th class="col-sm-2 text-right">{{ trans_choice('general.totals', 1) }}</th>
                </tr>
            </thead>
        </table>
        <table class="table table-hover" style="margin-top: 40px">
            <thead>
                <tr>
                    <th class="col-sm-2" colspan="6">{{ trans_choice('general.incomes', 1) }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totals['income_categories'] as $category)
                    <tr>
                        <td class="col-sm-2">{{ $category['name'] }}</td>
                        <td class="col-sm-2 text-right">@money($category['amount'], setting('general.default_currency'), true)</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="col-sm-2">{{ trans('reports.gross_profit') }}</th>
                    <th class="col-sm-2 text-right">@money($totals['incomes']['amount'], setting('general.default_currency'), true)</th>
                </tr>
            </tfoot>
        </table>

        <table class="table table-hover" style="margin-top: 40px">
            <thead>
                <tr>
                    <th class="col-sm-2" colspan="6">{{ trans_choice('general.expenses', 2) }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totals['expense_categories'] as $category)
                    <tr>
                        <td class="col-sm-2">{{ $category['name'] }}</td>
                        <td class="col-sm-2 text-right">@money($totals['expenses']['amount'], setting('general.default_currency'), true)</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="col-sm-2">{{ trans('reports.total_expenses') }}</th>
                    <th class="col-sm-2 text-right">@money($totals['expenses']['amount'], setting('general.default_currency'), true)</th>
                </tr>
            </tfoot>
        </table>

        <table class="table" style="margin-top: 40px">
            <tbody>
                <tr>
                    <th class="col-sm-2" colspan="6">{{ trans('reports.net_profit') }}</th>
                    <th class="col-sm-2 text-right"><span>@money($totals['total']['amount'], $totals['total']['currency_code'], true)</span></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
