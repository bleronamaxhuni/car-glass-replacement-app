<?php

namespace App\Http\Controllers;

use App\Http\Requests\Quote\StoreQuoteRequest;
use App\Http\Requests\Quote\VendorOptionsRequest;
use App\Services\QuoteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuoteController extends Controller
{
    /**
     * @param QuoteService $quoteService
     */
    public function __construct(
        private QuoteService $quoteService
    ) {}

    /**
     * Show the initial quote creation form.
     *
     * @return View
     */
    public function create(): View
    {
        return view('quotes.create');
    }

    /**
     * Show vendor options for the selected car and glass type.
     *
     * @param VendorOptionsRequest $request
     * @return View|RedirectResponse
     */
    public function vendorOptions(VendorOptionsRequest $request): View|RedirectResponse
    {
        $result = $this->quoteService->getVendorOptions($request->validated());

        if (isset($result['error'])) {
            $message = $result['error'] === 'body_type'
                ? 'Selected body type is not supported.'
                : 'No vendor options are available for the selected glass type.';
            $key = $result['error'] === 'body_type' ? 'body_type' : 'glass_type_id';

            return back()->withErrors([$key => $message])->withInput();
        }

        return view('quotes.vendor-options', [
            'car' => $result['car'],
            'glassType' => $result['glassType'],
            'vendorOptions' => $result['vendorOptions'],
        ]);
    }

    /**
     * Store a quote for the selected vendor option.
     *
     * @param StoreQuoteRequest $request
     * @return View
     */
    public function store(StoreQuoteRequest $request): View
    {
        $quote = $this->quoteService->createQuote($request->validated());

        return view('quotes.summary', [
            'quote' => $quote,
        ]);
    }
}
