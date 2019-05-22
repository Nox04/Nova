<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Income\Invoice;
use App\Models\Income\InvoicePayment;
use App\Models\Income\Revenue;
use App\Models\Expense\Bill;
use App\Models\Expense\BillPayment;
use App\Models\Expense\Payment;
use App\Models\Setting\Category;
use App\Traits\DateTime;
use Charts;
use Date;

class Daily extends Controller
{
    use DateTime;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = $totals = $categories = [];

        $now = Date::now();

        $day = request('day', $now->day. '-' .$now->month. '-' .$now->year);
        
        $income_categories = Category::enabled()->type('income')->orderBy('name')->pluck('name', 'id')->toArray();

        $expense_categories = Category::enabled()->type('expense')->orderBy('name')->pluck('name', 'id')->toArray();

        foreach($income_categories as $key => $income) {
            $totals['income_categories'][$key] = [
                'name' => $income,
                'amount' => 0,
                'currency_code' => setting('general.default_currency'),
                'currency_rate' => 1
            ];
        }

        foreach($expense_categories as $key => $expense) {
            $totals['expense_categories'][$key] = [
                'name' => $expense,
                'amount' => 0,
                'currency_code' => setting('general.default_currency'),
                'currency_rate' => 1
            ];
        }

        $totals['total'] = $totals['incomes'] = $totals['expenses'] = [
            'amount' => 0,
            'currency_code' => setting('general.default_currency'),
            'currency_rate' => 1
        ];

        // Invoices
        $invoices = InvoicePayment::between('paid_at')->with('invoice.items')->get();
        $this->setAmount($totals, $invoices, 'invoice', 'paid_at');

        
        // Revenues
        $revenues = Revenue::between('paid_at')->isNotTransfer()->get();
        $this->setAmount($totals, $revenues, 'revenue', 'paid_at');
        
        // Bills
        $bills = BillPayment::between('paid_at')->get();
        $this->setAmount($totals, $bills, 'bill', 'paid_at');
        
        // Payments
        $payments = Payment::between('paid_at')->isNotTransfer()->get();
        $this->setAmount($totals, $payments, 'payment', 'paid_at');
        
        $uniqueInvoices = Invoice::between('invoiced_at')->goOut()->with('items')->get();
        $this->setItems($items, $uniqueInvoices);

        // Check if it's a print or normal request
        if (request('print')) {
            $view_template = 'reports.daily.print';
        } else {
            $view_template = 'reports.daily.index';
        }

        return view($view_template, compact('totals', 'items'));
    }

    private function setAmount(&$totals, $items, $type, $date_field)
    {
        foreach ($items as $item) {
            if (($item->getTable() == 'bill_payments') || ($item->getTable() == 'invoice_payments')) {
                $type_item = $item->$type;

                $item->category_id = $type_item->category_id;
            }

            $now = Date::parse($item->$date_field);
            
            $date = $now->month. '-' .$now->day. '-' .$now->year;

            $group = (($type == 'invoice') || ($type == 'revenue')) ? 'income' : 'expense';

            $amount = $item->getConvertedAmount(false, false);


            // Forecasting
            if ((($type == 'invoice') || ($type == 'bill')) && ($date_field == 'due_at')) {
                foreach ($item->payments as $payment) {
                    $amount -= $payment->getConvertedAmount();
                }
            }

            if ($group == 'income') {
                $totals['incomes']['amount'] += $amount;
                $totals['income_categories'][$item->category_id]['amount'] += $amount;
                $totals['total']['amount'] += $amount;
            } else {
                $totals['expenses']['amount'] += $amount;
                $totals['expense_categories'][$item->category_id]['amount'] += $amount;
                $totals['total']['amount'] -= $amount;
            }
        }
    }

    private function setItems(&$items, $invoices)
    {
        if($invoices->count() > 0) {
            foreach ($invoices as $invoice) {
                foreach($invoice->items as $item) {
                    
                    if($item->item_id !== 0) {
                        if(array_key_exists($item->item_id,$items)) {
                            $items[$item->item_id]['quantity'] += $item->quantity;
                            $items[$item->item_id]['price'] += $item->quantity * $item->price;
                        } else {
                            $items[$item->item_id] = [
                                'name' => $item->name, 
                                'quantity' => $item->quantity, 
                                'price' => $item->quantity * $item->price
                            ];
                        }
                    }
                }
            }
        }
    }
}
