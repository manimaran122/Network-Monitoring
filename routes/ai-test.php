<?php

use App\Ai\Agents\MonitorAnalyzer;
use Illuminate\Support\Facades\Route;

Route::get('/test-ai', function () {
    try {
        // Test the MonitorAnalyzer agent with Gemini
        $response = MonitorAnalyzer::make()->prompt(
            'Analyze the current monitoring system status and provide insights.'
        );

        return response()->json([
            'success' => true,
            'provider' => 'Gemini',
            'model' => 'gemini-2.0-flash-exp',
            'response' => $response,
        ], 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
});
