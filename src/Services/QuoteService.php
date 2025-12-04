<?php
// src/Services/QuoteService.php

namespace Services;

/**
 * QuoteService handles the dynamic pricing and quotation calculation
 * based on the client's project features and selected package.
 */
class QuoteService
{
    // --- Pricing Constants --------------------------------------------------

    private const PRICING = [
        'Basic' => [
            'base'        => 1990.00,
            'hosting'     => 49.00,
            'extra_page'  => 150.00
        ],
        'Standard' => [
            'base'        => 3490.00,
            'hosting'     => 69.00,
            'extra_page'  => 120.00
        ],
        'Premium' => [
            'base'        => 4999.00,
            'hosting'     => 89.00,
            'extra_page'  => 100.00
        ]
    ];

    private const ADDONS = [
        'social_media' =>      ['Basic' => 250.00, 'Standard' => 0.00,    'Premium' => 0.00],
        'whatsapp' =>          ['Basic' => 150.00, 'Standard' => 0.00,    'Premium' => 0.00],
        'custom_form' =>       ['Basic' => 300.00, 'Standard' => 0.00,    'Premium' => 0.00],
        'complex_form' =>      ['Basic' => 600.00, 'Standard' => 600.00,  'Premium' => 0.00],
        'live_chat' =>         ['Basic' => 350.00, 'Standard' => 350.00,  'Premium' => 350.00],
        'ecommerce' =>         ['Basic' => 1999.00,'Standard' => 1999.00, 'Premium' => 1999.00],
        'booking' =>           ['Basic' => 700.00, 'Standard' => 700.00,  'Premium' => 700.00],
        'multi_language' =>    ['Basic' => 700.00, 'Standard' => 700.00,  'Premium' => 700.00],
    ];

    private const BASE_INCLUDED_PAGES = 5;

    // ------------------------------------------------------------------------
    //  MAIN QUOTE CALCULATION
    // ------------------------------------------------------------------------

    public function calculateQuote(array $formData): array
    {
        $selectedAddons    = $formData['addons'] ?? [];
        $pageCount         = isset($formData['pages']) ? (int)$formData['pages'] : 1;

        // Determine package based on selected features
        $packageName = $this->determineBasePackage($selectedAddons);
        $packageInfo = self::PRICING[$packageName];

        // --- Base Costs -----------------------------------------------------

        $baseCost      = $packageInfo['base'];
        $hosting       = $packageInfo['hosting'];
        $extraPageCost = $packageInfo['extra_page'];

        // --- Extra Page Calculation ----------------------------------------

        $extraPages = max(0, $pageCount - self::BASE_INCLUDED_PAGES);
        $extraPagesTotal = $extraPages * $extraPageCost;

        // --- Addons Calculation ---------------------------------------------

        $addonsBreakdown = [];
        $totalAddonsCost = 0;

        foreach ($selectedAddons as $addonKey) {
            if (!isset(self::ADDONS[$addonKey])) {
                continue; // ignore unknown addons
            }

            $cost = self::ADDONS[$addonKey][$packageName];
            $addonsBreakdown[$addonKey] = $cost;
            $totalAddonsCost += $cost;
        }

        // --- Grand Total ----------------------------------------------------

        $totalOnceOff = $baseCost + $extraPagesTotal + $totalAddonsCost;

        return [
            'package_name'         => $packageName,
            'base_cost'            => $this->money($baseCost),
            'hosting_monthly'      => $this->money($hosting),

            'pages_requested'      => $pageCount,
            'included_pages'       => self::BASE_INCLUDED_PAGES,
            'extra_pages'          => $extraPages,
            'extra_pages_total'    => $this->money($extraPagesTotal),

            'addons'               => $this->formatAddonOutput($addonsBreakdown),

            'addons_total'         => $this->money($totalAddonsCost),
            'total_once_off'       => $this->money($totalOnceOff),
        ];

        // Add Integrity Signature
        $result['signature'] = $this->generateQuoteSignature($result);
        
        return $result;
    }

    // ------------------------------------------------------------------------
    //  PACKAGE DETERMINATION LOGIC
    // ------------------------------------------------------------------------

    /**
     * Determines the minimum required package based on selected addons.
     * Upgrades the package if an addon requires higher tier.
     */
    private function determineBasePackage(array $addons): string
    {
        $package = 'Basic';

        foreach ($addons as $addonKey) {
            if (!isset(self::ADDONS[$addonKey])) {
                continue;
            }

            // If cost is 0 for Standard but not for Basic → promote to Standard
            if (self::ADDONS[$addonKey]['Standard'] === 0.00 
                && self::ADDONS[$addonKey]['Basic'] > 0) {
                $package = 'Standard';
            }

            // If cost is 0 for Premium → promote to Premium
            if (self::ADDONS[$addonKey]['Premium'] === 0.00) {
                $package = 'Premium';
            }
        }

        return $package;
    }

    // ------------------------------------------------------------------------
    //  HELPERS
    // ------------------------------------------------------------------------

    private function money(float $value): string
    {
        return number_format($value, 2, '.', '');
    }

    private function formatAddonOutput(array $addons): array
    {
        $out = [];
        foreach ($addons as $key => $value) {
            $out[$key] = $this->money($value);
        }
        return $out;
    }
    // ------------------------------------------------------------------------
    //  INTEGRITY & SECURITY
    // ------------------------------------------------------------------------

    private function generateQuoteSignature(array $quoteData): string
    {
        // Create a string of critical values to hash
        // We use a simple app secret (in a real app, use ENV)
        $secret = 'CLOUD27_APP_SECRET_KEY_v1'; 
        
        // Sort addons to ensure consistent ordering
        $addons = $quoteData['addons'] ?? [];
        ksort($addons);
        
        $dataToHash = implode('|', [
            $quoteData['total_once_off'],
            $quoteData['hosting_monthly'],
            json_encode($addons),
            $quoteData['package_name']
        ]);

        return hash_hmac('sha256', $dataToHash, $secret);
    }

    public static function getPricingData(): array
    {
        return [
            'pricing' => self::PRICING,
            'addons' => self::ADDONS,
            'base_included_pages' => self::BASE_INCLUDED_PAGES
        ];
    }
}
